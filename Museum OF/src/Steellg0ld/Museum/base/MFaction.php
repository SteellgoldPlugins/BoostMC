<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Server;
use pocketmine\utils\Timezone;
use Steellg0ld\Museum\json\JSONProvider;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class MFaction
{
    const ROLES = [
        0 => "Recrue",
        1 => "Membre",
        2 => "Officier",
        3 => "Chef"
    ];

    /**
     * @var bool|mixed
     */
    private $data;

    /**
     * MFaction constructor.
     * @param String $faction_id
     */
    public function __construct(string $faction_id)
    {
        $this->data = Plugin::getInstance()->getFactions()->getFactionConfig($faction_id)->get($faction_id);
    }

    /**
     *
     */
    public function update(){
        $provider = new JSONProvider();
        $provider->update($this->data["uniqid"],$this->data);
    }

    /**
     * @param String $identifier
     * @return bool
     */
    public static function factionExist(string $identifier): bool
    {
        return file_exists(Plugin::getInstance()->getDataFolder() . "factions/".$identifier.".json");
    }

    /**
     * @return array
     */
    public static function getAllFactions(): array{
        return Plugin::getInstance()->getFactions()->getFactions()->getAll();
    }

    /**
     * @param String $faction_id
     * @return bool|mixed
     */
    public static function getDataByIdentifier(String $faction_id) {
        return Plugin::getInstance()->getFactions()->getFactionConfig($faction_id)->get($faction_id);
    }

    /**
     * @param String $faction_id
     * @return mixed
     */
    public static function getNameByIdentifier(String $faction_id){
        return Plugin::getInstance()->getFactions()->getFactionConfig($faction_id)->get($faction_id)["name"];
    }

    /**
     * @param $member
     * @return string
     */
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
        Plugin::getInstance()->getDatabase()->getPlayerData($player)["faction_role"];
        var_dump(Database::$return);
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

    /**
     * @param string $player
     */
    public function addMember(string $player)
    {
        array_push($this->data["members"], $player);
        array_push($this->data["invitations_dates"], [$player => time()]);
        Plugin::getInstance()->getFactions()->update($this->data['uniqid'], $this->data);
    }

    /**
     * @param string $player
     */
    public function removeMember(string $player)
    {
        unset($this->data["members"][array_search($player, $this->data["members"])]);
        unset($this->data["invitations_dates"][$player]);
        Plugin::getInstance()->getFactions()->update($this->data['uniqid'], $this->data);
    }

    /**
     * @return mixed
     */
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
        foreach ($this->data["members"] as $member) {
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
     * @param bool $formated
     * @return string
     */
    public function getInvitedDate(string $member, bool $formated): string
    {
        return $formated ? str_replace(Utils::MONTHS["EN"], Utils::MONTHS["FR"],str_replace(Utils::DAYS["EN"], Utils::DAYS["FR"],date("l d M Y à H:i", $this->data['invitations_dates'][$member]))) : $this->data['invitations_dates'][$member];
    }

    /**
     * @return mixed
     */
    public function getIdentifier()
    {
        return $this->data["uniqid"];
    }

    /**
     * @param String $claims
     */
    public function updateClaim(String $claims)
    {
        $this->data["claims"] = $claims;
        $this->update();
    }
}