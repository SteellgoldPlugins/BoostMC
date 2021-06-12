<?php

namespace Steellg0ld\Museum\tasks;

use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Unicode;
use Steellg0ld\Museum\utils\Utils;

class CooldownTeleportTask extends Task{

    public Int $cooldown = 0;
    public Int $tick = 0;
    public Player $player;
    public Vector3 $vector3;
    public array $coords;

    public function __construct(Player $player, Int $cooldown, Vector3 $vector3, array $coords) {
        $this->player = $player;
        $this->cooldown = $cooldown;
        $this->vector3 = $vector3;
        $this->coords = $coords;
    }

    public function onRun(int $currentTick) {
        if(!$this->player->isOnline()){
            Plugin::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }

        $this->tick++;
        if($this->tick >= $this->cooldown){
            $this->player->teleport($this->vector3);
            $this->player->sendTip(Unicode::YELLOW_ACCEPT . Utils::getMessage($this->player, "TELEPORT_SUCCESSFULY",["{HOME}"],[Utils::getMessage($this->player,"FACTION_RESIDENCE")]) . Unicode::YELLOW_ACCEPT);
            Plugin::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }

        if((round($this->player->getX()) != round($this->coords["x"])) or (round($this->player->getY()) != round($this->coords["y"])) or (round($this->player->getZ()) != round($this->coords["z"]))){
            $this->player->sendTip(Unicode::SMALL_RED_BUTTON . Utils::getMessage($this->player, "TELEPORT_CANCELLED") . Unicode::SMALL_RED_BUTTON);
            Plugin::getInstance()->getScheduler()->cancelTask($this->getTaskId());
            return;
        }

        $unicode = $this->tick % 2 == true ? Unicode::TIMER : Unicode::TIMER_RIGHT;
        $this->player->sendTip($unicode . Utils::getMessage($this->player,"TELEPORT_COOLDOWN",["{SECONDS}"],[($this->cooldown - $this->tick)]) . $unicode);
    }
}