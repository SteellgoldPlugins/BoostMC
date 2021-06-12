<?php

namespace Steellg0ld\Museum\tasks;

use pocketmine\scheduler\Task;
use pocketmine\Server;
use Steellg0ld\Museum\base\Player;

class UpdateScoreboard extends Task {

    public function onRun(int $currentTick)
    {
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            if($player instanceof Player){
                $player->setScoreboard();
            }
        }
    }
}