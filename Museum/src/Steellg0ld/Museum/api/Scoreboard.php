<?php

namespace Steellg0ld\Museum\api;

use pocketmine\network\mcpe\protocol\RemoveObjectivePacket;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Server;
use Steellg0ld\Museum\base\Player;

class Scoreboard {
    /** @var array $scoreboards */
    public static $scoreboards = [];

    public function new(Player $player, string $objectiveName, string $displayName): void{
        if(isset(self::$scoreboards[$player->getName()])){
            self::remove($player);
        }
        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = "sidebar";
        $pk->objectiveName = $objectiveName;
        $pk->displayName = $displayName;
        $pk->criteriaName = "dummy";
        $pk->sortOrder = 0;
        $player->sendDataPacket($pk);
        self::$scoreboards[$player->getName()] = $objectiveName;
    }

    public function remove(Player $player): void{
        if(isset(self::$scoreboards[$player->getName()])){
            $objectiveName = self::getObjectiveName($player);
            $pk = new RemoveObjectivePacket();
            $pk->objectiveName = $objectiveName;
            $player->sendDataPacket($pk);
            unset(self::$scoreboards[$player->getName()]);
        }else{
            Server::getInstance()->getLogger()->warning("Trying to remove a scoreboard, but the player doesn't have an active scoreboard");
        }
    }

    public function setLine(Player $player, int $score, string $message): void{
        if(!isset(self::$scoreboards[$player->getName()])){
            return;
        }
        if($score > 15 || $score < 1){
            return;
        }
        $objectiveName = self::getObjectiveName($player);
        $entry = new ScorePacketEntry();
        $entry->objectiveName = $objectiveName;
        $entry->type = $entry::TYPE_FAKE_PLAYER;
        $entry->customName = $message;
        $entry->score = $score;
        $entry->scoreboardId = $score;
        $pk = new SetScorePacket();
        $pk->type = $pk::TYPE_CHANGE;
        $pk->entries[] = $entry;
        $player->sendDataPacket($pk);
    }

    public function getObjectiveName(Player $player): ?string{
        return isset(self::$scoreboards[$player->getName()]) ? self::$scoreboards[$player->getName()] : null;
    }

}