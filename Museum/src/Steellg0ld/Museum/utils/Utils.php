<?php

namespace Steellg0ld\Museum\utils;

use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\Plugin;

class Utils {
    CONST SEARCH = ["{PREFIX}", "{PRIMARY}", "{SECONDARY}"];
    CONST REPLACE = ["§l» §r§f", "§a", "§f"];

    public static function getPrefix(): string{
        return "§l» §r§f";
    }

    public static function sendMessage(Player $player, $message = "NO_MESSAGE_FOUND", $find = [], $replace = [])
    {
        $player->sendMessage(str_replace($find, $replace, Plugin::getInstance()->getMessages($player->getLang())->exists($message) ? str_replace(self::SEARCH, self::REPLACE, Plugin::getInstance()->getMessages($player->getLang())->get($message)) : "NO_MESSAGE_FOUND"));
    }
}