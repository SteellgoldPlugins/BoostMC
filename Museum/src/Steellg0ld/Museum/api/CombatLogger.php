<?php

namespace Steellg0ld\Museum\api;

use Steellg0ld\Museum\base\Player;

class CombatLogger{
    public static array $data = [];

    public static function isInCombat(Player $player): bool
    {
        if(array_key_exists($player->getName(),self::$data)){
            return true;
        }else{ return false; }
    }

    public static function setCombat(Player $player){
        self::$data[$player->getName()] = time() + 10;
    }

    public static function getTime(Player $player){
        return self::$data[$player->getName()] - time();
    }

    public static function delCombat(Player $player){
        unset(self::$data[$player->getName()]);
    }
}