<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Player;
use pocketmine\Server;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class MPlayer extends Player {
    public int $rank = 0;
    public int $money = 0;
    public string $code = "";
    public bool $hasJoinedWithCode = false;
    public string $enterCodeWaitEnd = "0";
    public string $encodedAddress = "";

    public string $faction_id = "";
    public int $faction_role = 0;

    public bool $hasFactionInvite;
    public array $invitations_infos = [
        "expiration" => "",
        "invitor" => "",
        "faction" => "",
        "role" => ""
    ];

    public function register(){
        Server::getInstance()->broadcastMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Bienvenu(e) à {PRIMARY}".$this->getName()."{SECONDARY}, qui se connecte pour la première fois"));
        Plugin::getInstance()->getDatabase()->playerRegister($this->getName(), $this->getAddress());
    }

    public function dataConnect(){
        $data = Plugin::getInstance()->getDatabase()->getPlayerData($this->getName());
        $this->rank = $data["rank"];
        $this->money = $data["money"];
        $this->faction_id = $data["faction"];
        if($data["faction"] !== "none") {
            $this->faction_role = 2;
        }
        $this->code = $data["code"];
        $this->hasJoinedWithCode = $data["hasJoinedWithCode"];
        $this->enterCodeWaitEnd = $data["enterCodeWaitEnd"];
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

    public function getSponsorCode(): string{
        return $this->code;
    }

    public function hasJoinedCode(): bool{
        return $this->hasJoinedWithCode;
    }

    public function getDecodedAddress(){
        return base64_decode(base64_decode(base64_decode(base64_decode($this->encodedAddress))));
    }
}