<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Player;

class MPlayer extends Player {

    public string $faction_id = "";
    public string $faction_role = "";

    public function getFaction(): MFaction {
        return new MFaction($this->faction_id);
    }

    /**
     * @return bool
     */
    public function hasFaction(): bool {
        return $this->faction_id !== null;
    }
}