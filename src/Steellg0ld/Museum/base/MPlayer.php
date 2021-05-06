<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Player;
use pocketmine\Server;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class MPlayer extends Player {
    public int $rank = 0;
    public int $money = 0;

    public string $faction_id = "";
    public string $faction_role = "";

    public function register(){
        Server::getInstance()->broadcastMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Bienvenu(e) à {PRIMARY}".$this->getName()."{SECONDARY}, qui se connecte pour la première fois"));
        Plugin::getInstance()->getDatabase()->playerRegister($this->getName());
    }

    public function dataConnect(){
        $data = Plugin::getInstance()->getDatabase()->getPlayerData($this->getName());
        $this->rank = $data["rank"];
        $this->money = $data["money"];
        $this->faction_id = $data["faction"];
        if($data["faction"] !== "none"){
            $this->faction_role = 2;
        }
    }

    public function getFaction(): MFaction {
        return new MFaction($this->faction_id);
    }

    /**
     * @return bool
     */
    public function hasFaction(): bool {
        return $this->faction_id !== "none";
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