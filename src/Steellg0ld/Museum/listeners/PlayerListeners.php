<?php

namespace Steellg0ld\Museum\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\Plugin;

class PlayerListeners implements Listener {
    public function onCreation(PlayerCreationEvent $event){
        $event->setPlayerClass(MPlayer::class);
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $event->setJoinMessage("");
        if(!$player instanceof MPlayer) return;
        if(!$player->hasPlayedBefore()) $player->register();
        $player->dataConnect();
    }

    public function onQuit(PlayerQuitEvent $event){
        $event->setQuitMessage("");
        $player = $event->getPlayer();
        if(!$player instanceof MPlayer) return;
        Plugin::getInstance()->getDatabase()->updatePlayer($player->getName(),$player->rank, $player->money, $player->faction_id);
    }
}