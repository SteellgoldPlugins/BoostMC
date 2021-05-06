<?php

namespace Steellg0ld\Museum\base;


use pocketmine\Server;
use Steellg0ld\Museum\Plugin;

class Database{
    public function getDatabase(): \SQLite3 {
        return new \SQLite3(Plugin::getInstance()->getDataFolder() . "Database.db");
    }

    public function init(){
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS players (player TEXT, rank INT, faction TEXT)");
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS factions (identifier TEXT, name TEXT, members TEXT, owner TEXT)");
    }

    public function playerRegister(String $name){
        $this->getDatabase()->query("INSERT INTO players (player, rank, faction) VALUES ('$name', 0, 'none')");
    }

    public function factionRegister(String $identifier, String $name, String $owner){
        $this->getDatabase()->query("INSERT INTO factions (identifier, name, members, owner) VALUES ('$identifier', '$name', '$owner', '$owner')");
    }

    public function getPlayerData(String $name){
    $data = array();
    $query = self::getDatabase()->query("SELECT * FROM players WHERE name = '$name'");
        while ($res = $query->fetchArray(1)){
            array_push($data,$res);
        }
        return $data[0];
    }
}