<?php

namespace Steellg0ld\Museum\utils;

class Utils
{
    const CONSOLE_ERROR = "Vous n'êtes pas joueur, connectez vous en jeu pour pouvoir executer cette commande";
    const PERMISSION_ERROR = "Vous n'avez pas la permission d'utiliser ce système/commande";

    const SUCCESS_COLOR = "§a";
    const TEXT_COLOR = "§f";
    const ERROR_COLOR = "§c";
    const DANGER_COLOR = "§e";
    const PREFIX = ":";
    const ECONOMY_SYMBOL = "$";
    const FACTION_CREATE_PRICE = 500;

    const VPN_API_KEY = "e842f02fa26b42329f6f6497b5635aa7";
    const API_URL = "https://vpnapi.io/api/{ADDRESS}?key=" . self::VPN_API_KEY;
    public static array $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    /**
     * @param String $message
     * @param array $search
     * @param array $values
     * @return array|string|string[]
     */
    public static function createMessage(string $message, array $search = [], array $values = [])
    {
        $default = str_replace(["{DANGER}", "{PRIMARY}", "{SECONDARY}", "{ERROR}", "{PREFIX}", "{ECONOMY_SYMBOL}"], [self::DANGER_COLOR, self::SUCCESS_COLOR, self::TEXT_COLOR, self::ERROR_COLOR, self::PREFIX, self::ECONOMY_SYMBOL], $message);
        return $search !== null ? str_replace($search, $values, $default) : $default;
    }

    public static function checkVPN(string $address): bool
    {
        $response = file_get_contents(str_replace("{ADDRESS}", $address, self::API_URL), false, stream_context_create(self::$arrContextOptions));
        $response = json_decode($response);
        if ($response->security->vpn) {
            return true;
        } else {
            return false;
        }
    }
}