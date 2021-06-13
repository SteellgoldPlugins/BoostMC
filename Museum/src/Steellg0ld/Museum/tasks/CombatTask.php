<?php

namespace Steellg0ld\Museum\tasks;

use pocketmine\scheduler\Task;
use Steellg0ld\Museum\api\CombatLogger;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Unicode;
use Steellg0ld\Museum\utils\Utils;

class CombatTask extends Task{
    public $player;

    public function __construct(Player $player){
        $this->player = $player;
    }

    public function onRun(int $currentTick){
        if(!$this->player->isOnline()){
            Plugin::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }

        if(CombatLogger::$data[$this->player->getName()] >= time()){
            $this->player->inCombat = true;
            $this->player->setNameTag($this->player->getName()."\n".Unicode::HEARTH . " " . round($this->player->getHealth(),2) . " | " . Unicode::FOOD . " " . $this->player->getFood());
        }else{
            $this->player->inCombat = false;
            $this->player->setNameTag($this->player->getName());
            CombatLogger::delCombat($this->player);
            Plugin::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}