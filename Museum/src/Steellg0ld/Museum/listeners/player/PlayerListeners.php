<?php

namespace Steellg0ld\Museum\listeners\player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;
use Steellg0ld\Museum\api\VPN;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\utils\Utils;

class PlayerListeners implements Listener
{
    public function create(PlayerCreationEvent $event) {
        $event->setPlayerClass(Player::class);
    }

    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        if($player instanceof Player) {
            if(VPN::isVPN($player->getAddress())){
                $player->close(' ', "Vous utilisez un vpn");
            }
            if(!$player->hasPlayedBefore()) {
                Server::getInstance()->broadcastMessage(Utils::PREFIX_BASIC . "Le joueur " . $player->getName() . " a rejoin pour la première fois");
            }
        }
    }
}