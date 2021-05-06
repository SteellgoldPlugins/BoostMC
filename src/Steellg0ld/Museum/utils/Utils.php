<?php

namespace Steellg0ld\Museum\utils;

class Utils{
    CONST CONSOLE_ERROR = "Vous n'êtes pas joueur, connectez vous en jeu pour pouvoir executer cette commande";
    CONST PERMISSION_ERROR = "Vous n'avez pas la permission d'utiliser ce système/commande";

    CONST SUCCESS_COLOR = "§a";
    CONST TEXT_COLOR = "§f";
    CONST ERROR_COLOR = "§c";
    CONST DANGER_COLOR = "§e";
    CONST PREFIX = ":";
    CONST ECONOMY_SYMBOL = "$";
    CONST FACTION_CREATE_PRICE = 500;

    /**
     * @param String $message
     * @param array $search
     * @param array $values
     * @return array|string|string[]
     */
    public static function createMessage(String $message, array $search = [], array $values = []){
        $default = str_replace(["{DANGER}", "{PRIMARY}", "{SECONDARY}", "{ERROR}", "{PREFIX}", "{ECONOMY_SYMBOL}"], [self::DANGER_COLOR, self::SUCCESS_COLOR, self::TEXT_COLOR, self::ERROR_COLOR, self::PREFIX, self::ECONOMY_SYMBOL], $message);
        return $search !== null ? str_replace($search, $values, $default) : $default;
    }
}