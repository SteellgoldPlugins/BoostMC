<?php

namespace Steellg0ld\Museum\base;


use pocketmine\nbt\tag\StringTag;
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
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS players (player TEXT, address TEXT, rank INT, money INT, faction TEXT, faction_role INT, code TEXT, hasJoinedWithCode BOOL, enterCodeWaitEnd TEXT)");
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS factions (identifier TEXT, name TEXT, members TEXT, owner TEXT)");
    }

    /**
     * @param String $name
     * @param String $address
     */
    public function playerRegister(String $name, String $address){
        $time = time() + 60 * 60 * 24 * 3;
        $address = base64_encode(base64_encode(base64_encode(base64_encode($address))));
        $this->getDatabase()->query("INSERT INTO players (player, address, rank, money, faction, faction_role, code, hasJoinedWithCode, enterCodeWaitEnd) VALUES ('$name', '$address', 0, 0, 'none', 0, 'none', 'none', '$time')");
    }

    /**
     * @param String $identifier
     * @param String $name
     * @param String $owner
     */
    public function factionRegister(String $identifier, String $name, String $owner){
        $factions = Plugin::getInstance()->getFactions();
        $factions->set($name,$identifier);
        $factions->save();

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
     * @param string $faction
     * @param string $code
     * @param bool $hasJoinedWithCode
     * @param string $enterCodeWaitEnd
     */
    public function updatePlayer(String $name, Int $rank = 0, Int $money = 250, String $faction = "none", Int $faction_role = 0, String $code = "none", Bool $hasJoinedWithCode = false, String $enterCodeWaitEnd = "0"){
        $this->getDatabase()->query("UPDATE players SET rank = '$rank', money = '$money', faction = '$faction', faction_role = '$faction_role', code = '$code$', hasJoinedWithCode = '$hasJoinedWithCode', enterCodeWaitEnd = '$enterCodeWaitEnd' WHERE player = '$name'");
    }

    /**
     * @param String $members
     * @param String $faction
     */
    public function updateFactionMembers(String $members, String $faction){
        $this->getDatabase()->query("UPDATE factions SET members = '$members' WHERE identifier = '$faction'");
    }
}