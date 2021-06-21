<?php

namespace Steellg0ld\Museum\base;

use confidential\MySQL;
use Steellg0ld\Museum\Plugin;

class Database
{

    public function getDatabase(): \MySQLi {
        return new \MySQLi(MySQL::HOSTNAME,MySQL::USER,MySQL::PASSWORD,MySQL::DATABASE);
    }

    public function initialize(){
        Plugin::getInstance()->getDatabase()->getDatabase()->query("CREATE TABLE IF NOT EXISTS faction (faction VARCHAR(255) PRIMARY KEY, players TEXT, power int, money int, allies TEXT, description TEXT, claim_message TEXT, claims TEXT, chest TEXT, roles TEXT, dates TEXT, invests TEXT, home TEXT);");
        Plugin::getInstance()->getDatabase()->getDatabase()->query("CREATE TABLE IF NOT EXISTS players (player VARCHAR(255) PRIMARY KEY, address TEXT, faction TEXT, role int, rank int, money int, lang TEXT, settings TEXT, discordId TEXT);");
    }

    public function player_update(String $player, String $address, String $faction, Int $role, Int $rank, Int $money, String $lang, String $settings)
    {
        Plugin::getInstance()->getDatabase()->getDatabase()->query("UPDATE players SET address = '$address', faction = '$faction', role = '$role', rank = '$rank', money= '$money', lang = '$lang', settings = '$settings' WHERE player = '$player'");
    }

    public static function update(String $colum, String $value, String $player)
    {
        Plugin::getInstance()->getDatabase()->getDatabase()->query("UPDATE players SET '$colum'='$value' WHERE player = '$player'");
    }
}