<?php

namespace Steellg0ld\Museum\listeners;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\Plugin;

class FactionListeners implements Listener
{

    public function onBreak(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        if($player instanceof MPlayer) $player->canInteractClaim($event);
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        if($player instanceof MPlayer) $player->canInteractClaim($event);
    }
}