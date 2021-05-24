<?php

namespace Steellg0ld\Museum\utils;

use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\Plugin;

class Utils {
    CONST SEARCH = ["{PREFIX}", "{PRIMARY}", "{SECONDARY}", "{ERROR}", "{DARK_ERROR}"];
    CONST REPLACE = ["§l» §r§f", "§a", "§f", "§c", "§4"];

    public static function getPrefix(): string{
        return "§l» §r§f";
    }

    public static function sendMessage(Player $player, String $message = "NO_MESSAGE_FOUND", array $search = [], array $replace = []){
        $player->sendMessage(str_replace($search, $replace,Plugin::getInstance()->getMessages($player->getLang())->exists($message) ? str_replace(self::SEARCH, self::REPLACE, Plugin::getInstance()->getMessages($player->getLang())->get($message)) : "NO_MESSAGE_FOUND"));
    }

    public static function getMessage(Player $player, String $message = "NO_MESSAGE_FOUND", array $search = [], array $replace = []){
        return str_replace($search, $replace,Plugin::getInstance()->getMessages($player->getLang())->exists($message) ? str_replace(self::SEARCH, self::REPLACE, Plugin::getInstance()->getMessages($player->getLang())->get($message)) : "NO_MESSAGE_FOUND");
    }
}