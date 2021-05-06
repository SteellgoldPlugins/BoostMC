<?php

namespace Steellg0ld\Museum\base;


use pocketmine\Server;
use Steellg0ld\Museum\Plugin;

class Database{
    public function getDatabase(): \SQLite3{
        return new \SQLite3(Plugin::getInstance()->getDataFolder() . "Database.db");
    }

    public function init(){
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS players (player TEXT, rank INT, faction TEXT)");
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS factions (identifier TEXT, name TEXT, members TEXT, claims_file TEXT, owner TEXT)");
    }
}