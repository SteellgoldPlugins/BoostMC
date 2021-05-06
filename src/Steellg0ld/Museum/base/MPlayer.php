<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Player;
use pocketmine\Server;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class MPlayer extends Player {
    public array $data = [];

    public string $rank = "";
    public int $money = 0;

    public string $faction_id = "";
    public string $faction_role = "";

    public function register(){
        Server::getInstance()->broadcastMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Bienvenu(e) à {PRIMAR}".$this->getName()."{SECONDARY}, qui se connecte pour la première fois"));
        Plugin::getInstance()->getDatabase()->playerRegister($this->getName());
    }

    public function dataConnect(){
        $this->data = Plugin::getInstance()->getDatabase()->getPlayerData($this->getName());
        var_dump($this->data);
    }

    public function getData(): array{
        return $this->data;
    }

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