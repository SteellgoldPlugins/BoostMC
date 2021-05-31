<?php

namespace Steellg0ld\Museum\base;

use pocketmine\Server;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class Faction
{
    public static array $factions = [];
    public static array $claims = [];
    public static array $invitations = [];
    public static array $invitationsTimeout = [];

    public const RECRUE = 0;
    public const MEMBER = 1;
    public const OFFICIER = 2;
    public const LEADER = 3;

    public const ROLES = [
        0 => "RECRUE",
        1 => "MEMBER",
        2 => "OFFICIER",
        3 => "LEADER"
    ];

    public const DEFAULT_POWER = 20;
    public const DEFAULT_FACTION_PRICE = 500;
    public const POWER_PER_KILL = 5;
    public const POWER_PER_DEATHS = 10;
    public const INVITATION_EXPIRATION_TIME = 60;
    public const DEFAULT_ECONOMY_SYMBOL = 1;
    public const DEFAULT_MAX_MEMBERS = 10;

    public static function create(Player $player, string $faction, string $description, string $claim_message): void
    {
        self::$factions[$faction] = array("players" => array($player->getName()), "power" => self::DEFAULT_POWER, "money" => 0, "allies" => array(), "description" => $description, "claim_message" => $claim_message, "roles" => array($player->getName() => 3), "dates" => array($player->getName() => time()), "invests" => array($player->getName() => 0));
        self::$claims[$faction] = array();

        $player->faction = $faction;
        $player->faction_role = self::LEADER;

        $player->sendMessage(Utils::getMessage($player, "FACTION_CREATED", ["{FACTION}"],[$faction]));
    }

    public static function sendInvitation(Player $player, string $faction): void {
        self::$invitations[$player->getName()] = $faction;
        self::$invitationsTimeout[$player->getName()] = time() + self::INVITATION_EXPIRATION_TIME;
    }
}