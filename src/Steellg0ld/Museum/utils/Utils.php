<?php

namespace Steellg0ld\Museum\utils;

class Utils{
    CONST CONSOLE_ERROR = "Vous n'êtes pas joueur, connectez vous en jeu pour pouvoir executer cette commande";
    CONST PERMISSION_ERROR = "Vous n'avez pas la permission d'utiliser ce système/commande";

    CONST MESSAGE_FORMAT = "{PRIMARY}{PREFIX} {SECONDARY}{MESSAGE}";
    CONST SUCCESS_COLOR = "§a";
    CONST TEXT_COLOR = "§f";
    CONST ERROR_COLOR = "§c";
    CONST PREFIX = ":";

    /**
     * @param String $message
     * @param array $search
     * @param array $values
     * @return array|string|string[]
     */
    public static function createMessage(String $message, array $search = [], array $values = []){
        $default = str_replace(["{PRIMARY}", "{SECONDARY}", "{ERROR}", "{PREFIX}"], [self::SUCCESS_COLOR, self::TEXT_COLOR, self::ERROR_COLOR, self::PREFIX], self::MESSAGE_FORMAT);
        return $search !== null ? str_replace($search, $values, $default) : $default;
    }
}