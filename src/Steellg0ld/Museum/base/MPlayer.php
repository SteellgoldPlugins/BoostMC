<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Player;

class MPlayer extends Player {

    public string $faction_id = "";
    public string $faction_role = "";
    public int $money = 0;

    public function getFaction(): MFaction {
        return new MFaction($this->faction_id);
    }

    /**
     * @return bool
     */
    public function hasFaction(): bool {
        return $this->faction_id !== null;
    }

    public function getMoney(): int{
        return $this->money;
    }
}