<?php

namespace Steellg0ld\Museum\base;

use pocketmine\math\Vector3;
use Steellg0ld\Museum\api\CombatLogger;
use Steellg0ld\Museum\api\Scoreboard;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\tasks\async\RegisterPlayer;
use Steellg0ld\Museum\utils\Unicode;
use Steellg0ld\Museum\utils\Utils;

class Player extends \pocketmine\Player
{
    public int $rank = Ranks::PLAYER;
    public array $settings = [
        "player_status" => 1,
        "economy_symbol" => 1,
        "unicode" => 0,
        "armor_pvp_status" => false,
        "coords" => true
    ];


    public string $lang = "fr_FR";
    public int $money = 0;
    public string $faction = "none";
    public int $faction_role = 0;
    public string $discordId = "";
    public Vector3 $oldVector3;
    public bool $inCombat = false;
    public int $nextEnderPearl = 5;
    public string $oldClaim = "none";
    public string $inClaim = "none";
    public bool $map = false;
    public string $oldChunkX = "none";
    public string $oldChunkZ = "none";

    /**
     * @param Int ...$ranks
     * @return bool
     */
    public function hasRank(Int ...$ranks): bool{
        if(in_array($this->rank, $ranks)) {
            return true;
        } else {
            return false;
        }
    }

    public function assign(){
        $db = new Database();
        $data = $db->getDatabase()->query("SELECT * FROM players WHERE player = '" . $this->getName() . "'");
        while ($resultAttr = $data->fetch_array(MYSQLI_ASSOC)){
            $this->faction = $resultAttr['faction'];
            $this->faction_role = $resultAttr['role'];
            $this->rank = $resultAttr['rank'];
            $this->money = $resultAttr['money'];
            $this->settings = unserialize(base64_decode($resultAttr['settings']));
            $this->lang = $resultAttr['lang'];
            $this->discordId = $resultAttr['discordId'];
        }
    }

    public function register() {
        $this->rank = Ranks::PLAYER;
        $this->money = 0;
        $this->lang = "fr_FR";
        $this->settings["player_status"] = 1;
        $this->settings["armor_pvp_status"] = true;
        $this->settings["coords"] = true;
        $this->settings["unicode"] = 0;
        Plugin::getInstance()->getServer()->getAsyncPool()->submitTask(new RegisterPlayer($this->getName(), $this->getAddress()));
    }

    /**
     * @return string
     */
    public function getLang(): string{
        return $this->lang;
    }

    /**
     * @return int
     */
    public function getRank(): int{
        return $this->rank;
    }

    /**
     * @return string
     */
    public function getFaction() : string {
        return $this->faction;
    }

    /**
     * @return bool
     */
    public function hasFaction() : bool {
        return $this->faction !== "none";
    }

    public function setScoreboard(){
        $scoreboard = Plugin::getInstance()->getScoreboardAPI();
        if(!$this->inCombat){
            $scoreboard->remove($this);
            $scoreboard->new($this,"infos","MUSEUM");
            $scoreboard->setLine($this, 1,Unicode::COIN . " " . $this->money . " " . Economy::SYMBOLS[$this->settings["economy_symbol"]]);
            $scoreboard->setLine($this, 2,($this->hasRank(Ranks::HELPER,Ranks::MODERATOR,Ranks::ADMIN) ? Unicode::getMFace($this->settings["unicode"], $this->getRank()) . " " : " ") . Ranks::translate($this,$this->rank));
            $scoreboard->setLine($this, 3,Unicode::GROUP . ($this->faction == "none" ? " Sans faction" : " " .$this->faction));
            if($this->settings["coords"]) $scoreboard->setLine($this, 4,Unicode::MAP . " X: " . round($this->getX()). " Y: " . round($this->getY()). " Z: " . round($this->getZ()));
            if($this->inClaim !== "none") $scoreboard->setLine($this, 5, Unicode::IDLE . " Claim de: " . $this->inClaim);
        }else{
            $scoreboard->remove($this);
            $scoreboard->new($this,"infos","MUSEUM-PVP");
            $scoreboard->setLine($this, 1,Unicode::NETHERITE_SWORD . " " . CombatLogger::getTime($this) . " secondes");
            $scoreboard->setLine($this, 2,Unicode::ENDERPEARL_TIMER . (time() >= $this->nextEnderPearl ? " Disponible" : " ". ($this->nextEnderPearl - time()) . "s"));
        }

        if($this->settings["armor_pvp_status"]){
            $helmet = $this->getArmorUnicodes()[0] !== null ? $this->getArmorUnicodes()[0] . " " .(Utils::HELMET[$this->getArmorInventory()->getHelmet()->getId()] - $this->getArmorInventory()->getHelmet()->getDamage()) : Unicode::EMPTY_HELMET;
            $chestplate = $this->getArmorUnicodes()[1] !== null ? $this->getArmorUnicodes()[1] . " " .(Utils::CHESTPLATES[$this->getArmorInventory()->getChestplate()->getId()] - $this->getArmorInventory()->getChestplate()->getDamage()) : Unicode::EMPTY_CHESTPLATE;
            $leggings = $this->getArmorUnicodes()[2] !== null ? $this->getArmorUnicodes()[2] . " " .(Utils::LEGGINGS[$this->getArmorInventory()->getLeggings()->getId()] - $this->getArmorInventory()->getLeggings()->getDamage()) : Unicode::EMPTY_LEGGINGS;
            $boots = $this->getArmorUnicodes()[3] !== null ? $this->getArmorUnicodes()[3] . " " .(Utils::BOOTS[$this->getArmorInventory()->getBoots()->getId()] - $this->getArmorInventory()->getBoots()->getDamage()) : Unicode::EMPTY_BOOTS;
            $this->sendPopup($helmet . "  " . $chestplate . "  " . $leggings . "  " . $boots);
        }
    }

    public function getArmorUnicodes(): array {
        return [
            0 => $this->getArmorInventory()->getHelmet()->getId() !== 0 ? Unicode::ARMORS[$this->getArmorInventory()->getHelmet()->getId()] : null,
            1 => $this->getArmorInventory()->getChestplate()->getId() !== 0 ? Unicode::ARMORS[$this->getArmorInventory()->getChestplate()->getId()] : null,
            2 => $this->getArmorInventory()->getLeggings()->getId() !== 0 ? Unicode::ARMORS[$this->getArmorInventory()->getLeggings()->getId()] : null,
            3 => $this->getArmorInventory()->getBoots()->getId() !== 0 ?  Unicode::ARMORS[$this->getArmorInventory()->getBoots()->getId()]  : null
        ];
    }
}