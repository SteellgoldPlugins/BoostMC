<?php

namespace Steellg0ld\Museum\listeners\player;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use Steellg0ld\Museum\api\VPN;
use Steellg0ld\Museum\base\Faction;
use Steellg0ld\Museum\base\Faction as FactionAPI;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\base\Ranks;
use Steellg0ld\Museum\forms\shop\ShopForm;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Unicode;
use Steellg0ld\Museum\utils\Utils;

class PlayerListener implements Listener
{
    public const CHAT = "{RANK} {PRIMARY}[{SECONDARY}{FACTION}{FACTION_RANK}{PRIMARY}] {SECONDARY}{PLAYER_NAME} {PRIMARY}§l» §r{PRIMARY}{MESSAGE}";

    public function create(PlayerCreationEvent $event) {
        $event->setPlayerClass(Player::class);
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $event->setJoinMessage(" ");
        if($player instanceof Player) {
            $player->assign();

            Server::getInstance()->addOp($player->getName());
            if(VPN::isVPN($player->getAddress())){
                $player->close(' ', "§cMerci de ne pas utiliser de §lPROXY §r§cou de §c§lVPN");
                return;
            }

            if(!$player->hasPlayedBefore()) {
                $player->register();
            }

            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                if ($players instanceof Player) {
                    if ($players->settings["player_status"]) Utils::sendMessage($players, $player->hasPlayedBefore() ? "PLAYER_JOIN" : "PLAYER_FIRST_JOIN", ["{PLAYER}"], [$player->getName()],false, true);
                }
            }

            ShopForm::open($player);
            $player->setScoreboard();
        }
    }

    public function onLeave(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $event->setQuitMessage("");
        if ($player instanceof Player) {
            Plugin::getInstance()->getDatabase()->player_update($player->getName(),base64_encode(base64_encode(base64_encode($player->getAddress()))),$player->faction,$player->faction_role,$player->rank,$player->money,$player->lang,base64_encode(serialize($player->settings)),$player->discordId);
            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                if ($players instanceof Player) {
                    if ($players->settings["player_status"]) Utils::sendMessage($players, "PLAYER_LEAVE", ["{PLAYER}"], [$player->getName()], false, true);
                }
            }
        }
    }

    public function onChat(PlayerChatEvent $event){
        $event->setCancelled();
        $p = $event->getPlayer();
        if(!$p instanceof Player) return;
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            if($player instanceof Player) $player->sendMessage(str_replace(["{PRIMARY}", "{SECONDARY}", "{RANK}", "{FACTION}", "{FACTION_RANK}", "{PLAYER_NAME}", "{MESSAGE}"],[Ranks::$ranks[$p->getRank()]["p"], Ranks::$ranks[$p->getRank()]["s"], $p->hasRank(Ranks::HELPER, Ranks::MODERATOR, Ranks::ADMIN) ? Unicode::getMFace($p->settings["unicode"],$p->rank) : Unicode::COW, ($p->getFaction() == "none" ? Utils::getMessage($player,"SF") : $p->getFaction()),str_repeat("*",$p->faction_role),$p->getName(),$event->getMessage()],self::CHAT));
        }
    }

    public function onMove(PlayerMoveEvent $event){
        $p = $event->getPlayer();
        if(!$p instanceof Player) return;
        $chunk = $p->getLevel()->getChunkAtPosition($p);
        $chunkX = $chunk->getX();
        $chunkZ = $chunk->getZ();


        if($p->map == true){
            if($chunkX == $p->oldChunkX && $chunkZ == $p->oldChunkZ) {

            }else{
                $p->sendMessage(implode("\n", FactionAPI::getMap($p)));
                $p->oldChunkX = $chunkX;
                $p->oldChunkZ = $chunkZ;
            }
        }

        if(Faction::isInClaim($p->getLevel(),$chunkX,$chunkZ)){
            if(Faction::getFactionClaim($p->getLevel(),$chunkX,$chunkZ) !== $p->oldClaim){
                $p->oldClaim = Faction::getFactionClaim($p->getLevel(),$chunkX,$chunkZ);
                $p->inClaim = Faction::getFactionClaim($p->getLevel(),$chunkX,$chunkZ);

                $p->sendTip(Unicode::SHIELD . " ".Faction::$factions[Faction::getFactionClaim($p->getLevel(),$chunkX,$chunkZ)]["claim_message"]." ".Unicode::SHIELD);
            }
        }else{
            $p->oldClaim = "none";
            $p->inClaim = "none";
        }
    }

    public function onDeath(PlayerDeathEvent $event){
        $event->setDeathMessage(null);
        $cause = $event->getEntity()->getLastDamageCause();
        if ($cause instanceof EntityDamageByEntityEvent) {
            $killer = $cause->getDamager();
            $victim = $event->getPlayer();
            if($killer instanceof Player AND $victim instanceof Player){
                Server::getInstance()->broadcastTip("§e".$killer->getName() . " ".Unicode::DIAMOND_SWORD . " " . $victim->getName());
            }
        }
    }
}