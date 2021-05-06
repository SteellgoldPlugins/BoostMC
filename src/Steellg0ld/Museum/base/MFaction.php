<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\Plugin;

class MFaction{
    public $data;

    /**
     * MFaction constructor.
     * @param String $faction_id
     */
    public function __construct(String $faction_id){
        $this->data = Plugin::getInstance()->getDatabase()->getFactionData($faction_id);
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
     * @return mixed
     */
    public function getName(){
        return $this->data["name"];
    }

    /**
     * @param bool $active
     * @return int
     */
    public function getFactionClaims(bool $active): int{
        return $active ? 9 : 6;
    }
}