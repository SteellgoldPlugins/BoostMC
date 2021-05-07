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

    CONST VPN_API_KEY = "e842f02fa26b42329f6f6497b5635aa7";
    CONST API_URL = "https://vpnapi.io/api/{ADDRESS}?key=".self::VPN_API_KEY;
    public static $arrContextOptions =array(
        "ssl"=>array(
            "verify_peer"=>false,
            "verify_peer_name"=>false,
        ),
    );

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

    public static function checkVPN(String $address): bool{
        $response = file_get_contents(str_replace("{ADDRESS}", $address, self::API_URL),false,stream_context_create(self::$arrContextOptions));
        $response = json_decode($response);
        if($response->security->vpn) {
            return true;
        } else {
            return false;
        }
    }
}