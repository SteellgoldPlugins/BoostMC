<?php

namespace Steellg0ld\Museum\base;

class Player extends \pocketmine\Player
{
    public int $rank = Ranks::PLAYER;
        "player_status" => 1
    ];

    public string $lang = "fr_FR";
    public int $money = 0;
    public string $faction = "";
    public int $faction_role = 0;

    public function hasRank(Int ...$ranks): bool{
        if(in_array($this->rank, $ranks)) {
            return true;
        } else {
            return false;
        }
    }

    public function assign(){
        $data = Plugin::getInstance()->getDatabase()->getDatabase()->query("SELECT faction,role,rank,money,lang,settings FROM players WHERE player = '" . $this->getName() . "'");
        while ($resultAttr = $data->fetchArray(SQLITE3_ASSOC)){
            $this->faction = $resultAttr['faction'];
            $this->faction_role = $resultAttr['role'];
            $this->rank = $resultAttr['rank'];
            $this->money = $resultAttr['money'];
            $this->settings = unserialize(base64_decode($resultAttr['settings']));
            $this->lang = $resultAttr['lang'];
        }
    }

    public function register() {
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