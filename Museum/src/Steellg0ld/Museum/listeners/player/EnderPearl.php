<?php

namespace Steellg0ld\Museum\listeners\player;

use pocketmine\event\entity\ProjectileLaunchEvent;
use pocketmine\event\Listener;
use Steellg0ld\Museum\base\Player;
use pocketmine\entity\projectile\EnderPearl as EP;

class EnderPearl implements Listener{
    public function enderpearlLauncheEvent(ProjectileLaunchEvent $event){
        $entity = $event->getEntity();
        if ($entity instanceof EP){
            $player = $entity->getOwningEntity();
            if ($player instanceof Player and $player->isOnline()) {
                if(time() >= $player->nextEnderPearl){
                    $player->nextEnderPearl = time() + 5;
                }else{
                    $event->setCancelled();
                }
            }
        }
    }
}