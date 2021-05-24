<?php

namespace Steellg0ld\Museum\api;

class VPN {
    const VPN_API_KEY = "e842f02fa26b42329f6f6497b5635aa7";
    const API_URL = "https://vpnapi.io/api/{ADDRESS}?key=" . self::VPN_API_KEY;

    public static array $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    public static function isVPN(string $address): bool
    {
        $response = file_get_contents(str_replace("{ADDRESS}", $address, self::API_URL), false, stream_context_create(self::$arrContextOptions));
        $response = json_decode($response);
        if ($response->security->vpn) return true; else return false;
    }
}