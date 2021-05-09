<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Server;
use Steellg0ld\Museum\Plugin;

class MFaction
{
    const ROLES = [
        0 => "Recrue",
        1 => "Membre",
        2 => "Officier",
        3 => "Chef"
    ];
    public $data;

    /**
     * MFaction constructor.
     * @param String $faction_id
     */
    public function __construct(string $faction_id)
    {
        $this->data = Plugin::getInstance()->getDatabase()->getFactionData($faction_id);
    }

    /**
     * @param String $identifier
     * @return bool
     */
    public static function factionExist(string $identifier): bool
    {
        $factions = Plugin::getInstance()->getFactions();
        return $factions->exists($identifier);
    }

    public static function getAllFactions(): array{
        Plugin::getInstance()->getAsyncDatabase()->executeSelectRaw("SELECT identifier FROM `factions`", [], function (array $rows) {
            var_dump($rows);
            return $rows;
        });
    }

    public static function getDataByIdentifier(String $identifier) {
        return Plugin::getInstance()->getDatabase()->getFactionData($identifier);
    }

    public static function getNameByIdentifier($faction){
        return Plugin::getInstance()->getDatabase()->getFactionData($faction)["name"];
    }

    public static function playerStatus($member): string
    {
        $player = Server::getInstance()->getPlayer($member);
        if ($player instanceof MPlayer) {
            return "§a§lCONNECTÉ";
        } else {
            return "§c§lDÉCONNECTÉ";
        }
    }

    /**
     * @param MPlayer $player
     * @return string
     */
    public function getFactionRole(MPlayer $player): string
    {
        return $player->faction_role !== null;
    }

    /**
     * @param String $player
     * @return int
     */
    public function getOfflinePlayerFactionRole(string $player): int
    {
        return Plugin::getInstance()->getDatabase()->getPlayerData($player)["faction_role"];
    }

    /**
     * @param MPlayer $player
     * @param string ...$role
     * @return string
     */
    public function getFactionAccess(MPlayer $player, string ...$role): string
    {
        return in_array($player->faction_role, $role) ? "§a§lACCÈS" : "§c§lNON-ACCÈS";
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->data["name"];
    }

    public function addMember(string $player)
    {
        $members = explode(" ", $this->data["members"]);
        array_push($members, $player);
        $members = implode(" ", $members);
        Plugin::getInstance()->getDatabase()->updateFactionMembers($members, $this->data["identifier"]);
    }

    public function removeMember(string $player)
    {
        $array = explode(' ', $this->data["members"]);
        unset($array[array_search($player, $array)]);
        $new = implode(" ", $array);
        Plugin::getInstance()->getDatabase()->updateFactionMembers($new, $this->data["identifier"]);
    }

    public function getMembers()
    {
        return $this->data["members"];
    }

    /**
     * @param bool $active
     * @return int
     */
    public function getFactionClaims(bool $active): int
    {
        return $active ? 9 : 6;
    }

    /**
     * @param bool $connected
     * @return int
     */
    public function getMembersCount(bool $connected): int
    {
        $playerConnecteds = 0;
        $playerDisconnecteds = 0;
        foreach (explode(' ', $this->data["members"]) as $member) {
            if (Server::getInstance()->getPlayer($member) instanceof MPlayer) {
                $playerConnecteds++;
            } else {
                $playerDisconnecteds++;
            }
        }
        return $connected ? $playerConnecteds : $playerDisconnecteds;
    }

    /**
     * @param String $member
     * @return string
     */
    public function getInvitedDate(string $member): string
    {
        return "25 Janvier 2021";
    }

    public function getIdentifier()
    {
        return $this->data["identifier"];
    }
}