<?php

namespace Steellg0ld\Museum\listeners\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use Steellg0ld\Museum\api\VPN;
use Steellg0ld\Museum\base\Player;
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

            /**
             * SEND THE JOIN MESSAGE
             */
            if(!$player->hasPlayedBefore()) {
                foreach (Server::getInstance()->getOnlinePlayers() as $players){
                    if($players instanceof Player){
                        if($players->settings["player_status"]) Utils::sendMessage($players, "PLAYER_FIRST_JOIN", ["{PLAYER}"], [$player->getName()]);
                    }
                }
            }else{
                foreach (Server::getInstance()->getOnlinePlayers() as $players){
                    if($players instanceof Player){
                        if($players->notifications["player_status"]) Utils::sendMessage($players, "PLAYER_JOIN", ["{PLAYER}"], [$player->getName()]);
                    }
                }
            }
        }
    }

    public function onLeave(PlayerQuitEvent $event)
    {
        $player = $event->getPlayer();
        $event->setQuitMessage("");
        if ($player instanceof Player) {
            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                if ($players instanceof Player) {
                    if ($players->settings["player_status"]) Utils::sendMessage($players, "PLAYER_LEAVE", ["{PLAYER}"], [$player->getName()]);
                }
            }
        }
    }
}