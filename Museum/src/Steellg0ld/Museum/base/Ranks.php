<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\Plugin;

class Ranks {
    CONST PLAYER = 0;
    CONST VIP = 1;
    CONST VIP_PLUS = 2;
    CONST STAFF = 3;

    public static array $ranks = [
        0 => ["p" => "§f", "s" => "§7", "r" => "J"],
        1 => ["p" => "§g", "s" => "§e", "r" => "V"],
        2 => ["p" => "§2", "s" => "§a", "r" => "V+"],
        3 => ["p" => "§4", "s" => "§c", "r" => "S"]
    ];

    public static function translate(Player $player, Int $rank){
        $config = Plugin::getInstance()->getMessages($player->getLang());
        return $config->exists("RANK_".$rank) ? $config->get("RANK_".$rank) : $config->get("NO_RANK_FOUND");
    }
}