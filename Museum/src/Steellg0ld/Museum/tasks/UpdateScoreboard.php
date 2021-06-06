<?php

namespace Steellg0ld\Museum\tasks;

use pocketmine\scheduler\Task;
use Steellg0ld\Museum\Plugin;

class UpdateScoreboard extends Task {

    public function onRun(int $currentTick)
    {
        Plugin::getInstance()->getServer()->getAsyncPool()->submitTask(new \Steellg0ld\Museum\tasks\async\UpdateScoreboard());
    }
}