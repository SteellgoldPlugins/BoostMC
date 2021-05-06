<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Player;

class MPlayer extends Player {

    public string $rank = "";
    public int $money = 0;

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

    /**
     * @return int
     */
    public function getMoney(): int{
        return $this->money;
    }

    /**
     * @return string
     */
    public function getRank(): string{
        return $this->rank;
    }

    public function hasRank(String ...$ranks): bool{
        return in_array($this->rank, $ranks);
    }
}