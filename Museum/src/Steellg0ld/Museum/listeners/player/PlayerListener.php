<?php

namespace Steellg0ld\Museum\listeners\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use Steellg0ld\Museum\api\VPN;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class PlayerListener implements Listener
{
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

            foreach (Server::getInstance()->getOnlinePlayers() as $players){
                if($players instanceof Player) if($players->settings["player_status"]) Utils::sendMessage($players, $player->hasPlayedBefore() ? "PLAYER_JOIN" : "PLAYER_FIRST_JOIN", ["{PLAYER}"], [$player->getName()]);
            }
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
                    if ($players->settings["player_status"]) Utils::sendMessage($players, "PLAYER_LEAVE", ["{PLAYER}"], [$player->getName()]);
                }
            }
        }
    }
}