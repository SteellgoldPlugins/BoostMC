<?php

namespace Steellg0ld\Museum\listeners;

use pocketmine\block\Chest;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Event;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\inventory\FactionChestInventory;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

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

    public function onTap(PlayerInteractEvent $event){
        if(!$event->getBlock() instanceof Chest) return;

        $player = $event->getPlayer();
        if(!$player instanceof MPlayer) return;
        $chunk = $player->getLevel()->getChunkAtPosition($player);
        if(!Plugin::getInstance()->getClaims()->isInClaim($player->getLevel(), $chunk->getX(),$chunk->getZ())) return;
        $faction = Plugin::getInstance()->getFactions()->getFactionConfig(Plugin::getInstance()->getClaims()->getFactionClaim($player->getLevel(), $chunk->getX(),$chunk->getZ()));
        if($player->getFaction()->getIdentifier() == Plugin::getInstance()->getClaims()->getFactionClaim($player->getLevel(), $chunk->getX(),$chunk->getZ())){
            FactionChestInventory::openInventory($player, $player->getFaction());
        }else{
            $player->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous n'avez pas le rôle requis pour accéder au coffre de faction"));
        }
    }
}