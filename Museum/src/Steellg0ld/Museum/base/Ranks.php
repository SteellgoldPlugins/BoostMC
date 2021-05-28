<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\Plugin;

class Ranks {
    CONST PLAYER = 0;
    CONST VIP = 1;
    CONST VIP_PLUS = 2;
    CONST STAFF = 3;


    public static function translate(Player $player, Int $rank){
        $config = Plugin::getInstance()->getMessages($player->getLang());
        return $config->exists("RANK_".$rank) ? $config->get("RANK_".$rank) : $config->get("NO_RANK_FOUND");
    }
}