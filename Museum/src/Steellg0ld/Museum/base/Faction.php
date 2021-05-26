<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Server;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class Faction
{
    public static array $factions = [];
    public static array $claims = [];

    public const RECRUE = 0;
    public const MEMBER = 1;
    public const OFFICIER = 2;
    public const LEADER = 3;

    public const DEFAULT_POWER = 20;
    public const POWER_PER_KILL = 5;
    public const POWER_PER_DEATHS = 10;
    public const INVITATION_EXPIRATION_TIME = 60;

    public static function create(Player $player, string $faction): void
    {
        self::$factions[$faction] = array("players" => array($player->getName()), "power" => self::DEFAULT_POWER, "money" => 0, "allies" => array());
        self::$claims[$faction] = array();
        $player->faction = $faction;
        $player->faction_role = self::LEADER;

        $player->sendMessage(Utils::getMessage($player, "FACTION_CREATED", ["{FACTION}"],[$faction]));
    }
}