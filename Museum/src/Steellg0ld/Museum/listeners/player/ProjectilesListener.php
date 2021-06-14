<?php

namespace Steellg0ld\Museum\listeners\player;

use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\EnderPearl;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use Steellg0ld\Museum\base\Player;
use pocketmine\entity\projectile\EnderPearl as EP;

class ProjectilesListener implements Listener{
    public function enderPearlLauncheEvent(ProjectileLaunchEvent $event){
        $entity = $event->getEntity();
        if ($entity instanceof EP){
            $player = $entity->getOwningEntity();
            if ($player instanceof Player and $player->isOnline()) {
                if(time() >= $player->nextEnderPearl){
                    $player->nextEnderPearl = time() + 5;
                }else{
                    $player->getInventory()->addItem(Item::get(ItemIds::ENDER_PEARL));
                    $event->setCancelled();


                }
            }
        }
    }
}