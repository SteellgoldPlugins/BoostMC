<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\Plugin;

class Database
{
    public function getDatabase(): \SQLite3{
        return new \SQLite3(Plugin::getInstance()->getDataFolder() . Plugin::FILE_DB);
    }

    public function initialize(){
        Plugin::getInstance()->getDatabase()->getDatabase()->query("CREATE TABLE IF NOT EXISTS faction (faction VARCHAR(255) PRIMARY KEY, players TEXT, power int, money int, allies TEXT, claims TEXT, chest TEXT);");
        Plugin::getInstance()->getDatabase()->getDatabase()->query("CREATE TABLE IF NOT EXISTS players (player VARCHAR(255) PRIMARY KEY, address TEXT, faction TEXT, role int, rank int, money int, lang TEXT, settings TEXT);");
    }

    public function player_update()
    {
        Plugin::getInstance()->getDatabase()->getDatabase()->query("UPDATE players SET rank = '$rank', money = '$money', faction = '$faction', faction_role = '$faction_role', code = '$code', hasJoinedWithCode = '$hasJoinedWithCode', enterCodeWaitEnd = '$enterCodeWaitEnd', discordId = '$discordId' WHERE player = '$name'");
    }
}