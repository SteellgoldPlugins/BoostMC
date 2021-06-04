<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\Plugin;

class Ranks {
    CONST PLAYER = 0;
    CONST VIP = 1;
    CONST VIP_PLUS = 2;
    CONST YOUTUBE = 3;
    CONST TWITCH = 4;
    CONST STAFF = 5;
    CONST ADMIN = 6;
    CONST RANKS = ["Joueur", "VIP", "VIP+", "Youtube", "Twitch", "Staff", "Administrateur"];

    public static array $ranks = [
        0 => ["p" => "§f", "s" => "§c", "r" => "J"],
        1 => ["p" => "§f", "s" => "§c", "r" => "V"],
        2 => ["p" => "§f", "s" => "§c", "r" => "V+"],
        3 => ["p" => "§f", "s" => "§c", "r" => "YT"],
        4 => ["p" => "§f", "s" => "§c", "r" => "T"],
        5 => ["p" => "§f", "s" => "§c", "r" => "S"],
        6 => ["p" => "§f", "s" => "§c", "r" => "A"]
    ];

    public static function translate(Player $player, Int $rank){
        $config = Plugin::getInstance()->getMessages($player->getLang());
        return $config->exists("RANK_".$rank) ? $config->get("RANK_".$rank) : $config->get("NO_RANK_FOUND");
    }
}