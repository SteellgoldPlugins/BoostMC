<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Unicode;

class Ranks {
    CONST PLAYER = 0;
    CONST VIP = 1;
    CONST VIP_PLUS = 2;
    CONST YOUTUBE = 3;
    CONST TWITCH = 4;
    CONST HELPER = 5;
    CONST MODERATOR = 6;
    CONST ADMIN = 7;

    public static array $ranks = [
        0 => ["p" => "§f", "s" => "§7", "r" => "J", Unicode::GRASS_FACE],
        1 => ["p" => "§f", "s" => "§e", "r" => "V", "u" => Unicode::COW],
        2 => ["p" => "§f", "s" => "§g", "r" => "V+", "u" => Unicode::GOLD_COW],
        3 => ["p" => "§f", "s" => "§4", "r" => "YT", "u" => Unicode::SMALL_RED_BUTTON],
        4 => ["p" => "§f", "s" => "§9", "r" => "T", "u" => Unicode::SMALL_PURPLE_BUTTON],
        5 => ["p" => "§f", "s" => "§3", "r" => "S", "u" => Unicode::MEN_HELPER_FACE],
        6 => ["p" => "§f", "s" => "§b", "r" => "S", "u" => Unicode::MODERATOR_FACE],
        7 => ["p" => "§f", "s" => "§c", "r" => "A", "u" => Unicode::MEN_ADMIN_FACE]
    ];

    public static function translate(Player $player, Int $rank){
        $config = Plugin::getInstance()->getMessages($player->getLang());
        return $config->exists("RANK_".$rank) ? $config->get("RANK_".$rank) : $config->get("NO_RANK_FOUND");
    }
}