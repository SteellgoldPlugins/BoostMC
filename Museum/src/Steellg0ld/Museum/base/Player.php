<?php

namespace Steellg0ld\Museum\base;

class Player extends \pocketmine\Player
{
    public int $rank = Ranks::PLAYER;
    public array $notifications = [
        "player_status" => 1
    ];

    public $lang = "fr_FR";

    public function hasRank(Int ...$ranks): bool{
        if(in_array($this->rank, $ranks)) {
            return true;
        } else {
            return false;
        }
    }

    public function register() {
        var_dump("register");
        $this->rank = Ranks::PLAYER;
        $this->money = 0;
        $this->lang = "fr_FR";
        $this->settings["player_status"] = 1;
        Plugin::getInstance()->getServer()->getAsyncPool()->submitTask(new RegisterPlayer($this->getName(), $this->getAddress()));
    }

    public function getLang(): string{
        return $this->lang;
    }

    public function getRank(): int{
        return $this->rank;
    }
}