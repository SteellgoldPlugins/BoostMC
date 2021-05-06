<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\Plugin;

class MFaction{

    public string $faction_id;
    public string $faction_name;

    public function __construct(String $factionId){
        $this->faction_id = $factionId;
        $this->faction_name = "LesProduitsLaitiers";
    }

    /**
     * @param $int
     */
    public static function exist(String $faction_name) : bool {
        return true;
    }

    /**
     * @return bool
     */
    public function getName(): bool{
        return $this->faction_id !== null;
    }

    /**
     * @param MPlayer $player
     * @return string
     */
    public function getFactionRole(MPlayer $player): string {
        return $player->faction_role !== null;
    }

    /**
     * @param MPlayer $player
     * @param string ...$role
     * @return string
     */
    public function getFactionAccess(MPlayer $player, string ...$role): string {
        return in_array($player->faction_role, $role) ? "§a§lACCÈS" : "§c§lNON-ACCÈS";
    }

    /**
     * @param bool $active
     * @return int
     */
    public function getFactionClaims(bool $active): int{
        return $active ? 9 : 6;
    }
}