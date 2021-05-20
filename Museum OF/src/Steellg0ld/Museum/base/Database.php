<?php

namespace Steellg0ld\Museum\base;


use Steellg0ld\Museum\Plugin;

class Database
{
    public function init()
    {
        Plugin::getInstance()->getAsyncDatabase()->executeGenericRaw("CREATE TABLE IF NOT EXISTS players (player TEXT, address TEXT, rank INT, money INT, faction TEXT, faction_role INT, code TEXT, hasJoinedWithCode BOOL, enterCodeWaitEnd TEXT, lastJoin TEXT)");
    }

    public function initPlayer(MPlayer $player){
        $name = $player->getName();
        Plugin::getInstance()->getAsyncDatabase()->executeSelectRaw("SELECT * FROM players WHERE player = '$name'", [], function (array $rows) use ($player) {
            $player->rank = $rows[0]["rank"];
            $player->money = $rows[0]["money"];
            $player->faction_id = $rows[0]["faction"];
            if ($rows[0]["faction"] !== "none") {
                $player->faction_role = $rows[0]["faction_role"];
            }
            $player->code = $rows[0]["code"];
            $player->hasJoinedWithCode = $rows[0]["hasJoinedWithCode"];
            $player->enterCodeWaitEnd = $rows[0]["enterCodeWaitEnd"];
            $player->lastJoin = $rows[0]["lastJoin"];
        });
    }

    /**
     * @param String $name
     * @param String $address
     */
    public function playerRegister(string $name, string $address)
    {
        $time = time() + 60 * 60 * 24 * 3;
        $address = base64_encode(base64_encode(base64_encode(base64_encode($address))));
        $last = time();
        Plugin::getInstance()->getAsyncDatabase()->executeInsertRaw("INSERT INTO players (player, address, rank, money, faction, faction_role, code, hasJoinedWithCode, enterCodeWaitEnd, lastJoin) VALUES ('$name', '$address', 0, 0, 'none', 0, 'none', 'none', '$time', '$last')");
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
        Plugin::getInstance()->getAsyncDatabase()->executeGenericRaw("UPDATE players SET rank = '$rank', money = '$money', faction = '$faction', faction_role = '$faction_role', code = '$code', hasJoinedWithCode = '$hasJoinedWithCode', enterCodeWaitEnd = '$enterCodeWaitEnd' WHERE player = '$name'");
    }

    /**
     * @param String $members
     * @param String $faction
     */
    public function updateFactionMembers(string $members, string $faction)
    {
        var_dump("re-soon");
    }

    /**
     * @param string $name
     */
    public function getPlayerData(string $name)
    {
        Plugin::getInstance()->getAsyncDatabase()->executeSelectRaw("SELECT * FROM players WHERE player = '$name'", [], function (array $rows) {
            return $rows;
        });
    }
}