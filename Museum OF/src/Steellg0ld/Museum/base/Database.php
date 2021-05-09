<?php

namespace Steellg0ld\Museum\base;


use Steellg0ld\Museum\Plugin;

class Database
{
    public function init()
    {
        Plugin::getInstance()->getAsyncDatabase()->executeGenericRaw("CREATE TABLE IF NOT EXISTS players (player TEXT, address TEXT, rank INT, money INT, faction TEXT, faction_role INT, code TEXT, hasJoinedWithCode BOOL, enterCodeWaitEnd TEXT)");
        Plugin::getInstance()->getAsyncDatabase()->executeGenericRaw("CREATE TABLE IF NOT EXISTS factions (identifier TEXT, name TEXT, members TEXT, owner TEXT, claims TEXT)");
    }

    /**
     * @param String $name
     * @param String $address
     */
    public function playerRegister(string $name, string $address)
    {
        $time = time() + 60 * 60 * 24 * 3;
        $address = base64_encode(base64_encode(base64_encode(base64_encode($address))));
        Plugin::getInstance()->getAsyncDatabase()->executeInsertRaw("INSERT INTO players (player, address, rank, money, faction, faction_role, code, hasJoinedWithCode, enterCodeWaitEnd) VALUES ('$name', '$address', 0, 0, 'none', 0, 'none', 'none', '$time')");
    }

    /**
     * @param String $identifier
     * @param String $name
     * @param String $owner
     */
    public function factionRegister(string $identifier, string $name, string $owner)
    {
        $factions = Plugin::getInstance()->getFactions();
        $factions->set($name, $identifier);
        $factions->save();

        $claims = base64_encode(serialize(array()));
        Plugin::getInstance()->getAsyncDatabase()->executeInsertRaw("INSERT INTO factions (identifier, name, members, owner, claims) VALUES ('$identifier', '$name', '$owner', '$owner', '$claims')");
    }

    /**
     * @param String $name
     * @return mixed
     */
    public function getPlayerData(string $name)
    {
        Plugin::getInstance()->getAsyncDatabase()->executeSelectRaw("SELECT * FROM players WHERE player = '$name'", [], function (array $rows) {
            var_dump($rows);
            return $rows;
        });
    }

    /**
     * @param string $faction_id
     * @return mixed
     */
    public function getFactionData(string $faction_id)
    {
        Plugin::getInstance()->getAsyncDatabase()->executeSelectRaw("SELECT * FROM factions WHERE identifier = '$faction_id'", [], function(array $rows){
            var_dump($rows);
            return $rows;
        });

        /**
        while ($res = $query->fetchArray(1)) {
            array_push($data, $res);
        }
        return $data[0];
         **/
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
    public function updatePlayer(string $name, int $rank = 0, int $money = 250, string $faction = "none", int $faction_role = 0, string $code = "none", bool $hasJoinedWithCode = false, string $enterCodeWaitEnd = "0")
    {
        Plugin::getInstance()->getAsyncDatabase()->executeGenericRaw("UPDATE players SET rank = '$rank', money = '$money', faction = '$faction', faction_role = '$faction_role', code = '$code$', hasJoinedWithCode = '$hasJoinedWithCode', enterCodeWaitEnd = '$enterCodeWaitEnd' WHERE player = '$name'");
    }

    /**
     * @param String $members
     * @param String $faction
     */
    public function updateFactionMembers(string $members, string $faction)
    {
        Plugin::getInstance()->getAsyncDatabase()->executeGenericRaw("UPDATE factions SET members = '$members' WHERE identifier = '$faction'");
    }
}