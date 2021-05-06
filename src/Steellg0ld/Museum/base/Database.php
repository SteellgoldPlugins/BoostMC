<?php

namespace Steellg0ld\Museum\base;


use pocketmine\Server;
use Steellg0ld\Museum\Plugin;

class Database{
    /**
     * @return \SQLite3
     */
    public function getDatabase(): \SQLite3 {
        return new \SQLite3(Plugin::getInstance()->getDataFolder() . "Database.db");
    }

    public function init(){
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS players (player TEXT, rank INT, money INT, faction TEXT)");
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS factions (identifier TEXT, name TEXT, members TEXT, owner TEXT)");
    }

    /**
     * @param String $name
     */
    public function playerRegister(String $name){
        $this->getDatabase()->query("INSERT INTO players (player, rank, money, faction) VALUES ('$name', 0, 0, 'none')");
    }

    /**
     * @param String $identifier
     * @param String $name
     * @param String $owner
     */
    public function factionRegister(String $identifier, String $name, String $owner){
        $this->getDatabase()->query("INSERT INTO factions (identifier, name, members, owner) VALUES ('$identifier', '$name', '$owner', '$owner')");
    }

    /**
     * @param String $name
     * @return mixed
     */
    public function getPlayerData(String $name){
        $data = array();
        $query = self::getDatabase()->query("SELECT * FROM players WHERE player = '$name'");
        while ($res = $query->fetchArray(1)){
            array_push($data,$res);
        }
        return $data[0];
    }

    /**
     * @param string $faction_id
     * @return mixed
     */
    public function getFactionData(string $faction_id){
        $data = array();
        $query = self::getDatabase()->query("SELECT * FROM factions WHERE identifier = '$faction_id'");
        while ($res = $query->fetchArray(1)){
            array_push($data,$res);
        }
        return $data[0];
    }

    /**
     * @param String $name
     * @param Int $rank
     * @param Int $money
     * @param String $faction
     */
    public function updatePlayer(String $name, Int $rank, Int $money, String $faction){
        $this->getDatabase()->query("UPDATE players SET rank = '$rank', money = '$money', faction = '$faction' WHERE player = '$name'");
    }
}