<?php

namespace Steellg0ld\Museum\base;

use pocketmine\event\Event;
use pocketmine\Player;
use pocketmine\Server;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class MPlayer extends Player
{
    public int $rank = 0;
    public int $money = 0;
    public string $code = "";
    public bool $hasJoinedWithCode = false;
    public string $enterCodeWaitEnd = "0";
    public string $oldChunk = "0";
    public string $encodedAddress = "";

    public string $faction_id = "";
    public int $faction_role = 0;

    /**
     * PRIMARY SECONDARY
     */
    CONST RANKS_COLOR = [
        0 => "§f §7",
        1 => "§e §g",
        2 => "§b §3"
    ];

    CONST RANKS = [
        0 => "Joueur",
        1 => "VIP",
        2 => "Staff",
    ];

    public bool $hasFactionInvite;
    public array $invitations_infos = [
        "expiration" => "",
        "invitor" => "",
        "faction" => "",
        "role" => ""
    ];

    public function register()
    {
        Server::getInstance()->broadcastMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Bienvenu(e) à {PRIMARY}" . $this->getName() . "{SECONDARY}, qui se connecte pour la première fois"));
        Plugin::getInstance()->getDatabase()->playerRegister($this->getName(), $this->getAddress());
    }

    public function getFaction(): MFaction
    {
        return new MFaction($this->faction_id);
    }

    /**
     * @return bool
     */
    public function hasFaction(): bool
    {
        return $this->faction_id !== "none";
    }

    /**
     * @return int
     */
    public function getMoney(): int
    {
        return $this->money;
    }

    /**
     * @return string
     */
    public function getRank(): string
    {
        return $this->rank;
    }

    public function hasRank(string ...$ranks): bool
    {
        return in_array($this->rank, $ranks);
    }

    public function getSponsorCode(): string
    {
        return $this->code;
    }

    public function hasJoinedCode(): bool
    {
        return $this->hasJoinedWithCode;
    }

    public function getDecodedAddress()
    {
        return base64_decode(base64_decode(base64_decode(base64_decode($this->encodedAddress))));
    }

    public function sendTipFaction(Int $chunkXP, Int $chunkZP, String $msg){
        $this->sendTip("§fVous êtes dans le claim de §a" . MFaction::getNameByIdentifier(Plugin::getInstance()->getClaims()->getFactionClaim($this->getLevel(), $chunkXP, $chunkZP)) . $msg);
    }

    public function canInteractClaim(Event $event){
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if (!$player instanceof MPlayer) return;

        $toPos = $block->getLevel()->getChunkAtPosition($block->asVector3());
        $chunkXP = $toPos->getX();
        $chunkZP = $toPos->getZ();
        if (Plugin::getInstance()->getClaims()->isInClaim($block->getLevel(), $chunkXP, $chunkZP)) {
            $id = Plugin::getInstance()->getClaims()->getFactionClaim($block->getLevel(), $chunkXP, $chunkZP);
            if ($player->getFaction()->getIdentifier() !== $id) {
                $player->sendTip("§cProtégé par la §f" . MFaction::getNameByIdentifier($id));
                $event->setCancelled();
            }
        }
    }
}