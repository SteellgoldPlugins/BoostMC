<?php

namespace Steellg0ld\Museum\base;

use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Server;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\tasks\CooldownTeleportTask;
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
    public const DEFAULT_MAX_MEMBERS = 10;
    public const TELEPORT_COOLDOWN = 6;


    /**
         /$$      /$$
        | $$$    /$$$
        | $$$$  /$$$$  /$$$$$$  /$$$$$$$   /$$$$$$   /$$$$$$   /$$$$$$
        | $$ $$/$$ $$ |____  $$| $$__  $$ |____  $$ /$$__  $$ /$$__  $$
        | $$  $$$| $$  /$$$$$$$| $$  \ $$  /$$$$$$$| $$  \ $$| $$$$$$$$
        | $$\  $ | $$ /$$__  $$| $$  | $$ /$$__  $$| $$  | $$| $$_____/
        | $$ \/  | $$|  $$$$$$$| $$  | $$|  $$$$$$$|  $$$$$$$|  $$$$$$$
        |__/     |__/ \_______/|__/  |__/ \_______/ \____  $$ \_______/
                                                    /$$  \ $$
                                                    |  $$$$$$/
                                                    \______/
     */

    public static function create(Player $player, string $faction, string $description, string $claim_message): void
    {
        self::$factions[$faction] = array("players" => array($player->getName()), "power" => self::DEFAULT_POWER, "money" => 0, "allies" => array(), "description" => $description, "claim_message" => $claim_message, "roles" => array($player->getName() => 3), "dates" => array($player->getName() => time()), "invests" => array($player->getName() => 0), "home" => "none");
        self::$claims[$faction] = array();

        $player->faction = $faction;
        $player->faction_role = self::LEADER;

        $player->sendMessage(Utils::getMessage($player, "FACTION_CREATED", ["{FACTION}"],[$faction]));
    }

    public static function disbandFaction(String $faction): void {
        foreach (self::$factions[$faction]["players"] as $player){
            $p = Server::getInstance()->getPlayer($player);
            if($p instanceof Player){
                $p->faction = "none";
                $p->faction_role = 0;
            }else{
                Database::update("faction","none",$player);
                Database::update("faction_role",0,$player);
            }
        }

        if (isset(self::$factions[$faction]))unset(self::$factions[$faction]);
    }

    public static function getRoles(String $faction){
        return self::$factions[$faction]["roles"];
    }

    public static function getLeader(string $faction): string {
        foreach (self::getMembers($faction) as $player) {
            if (self::getRoles($faction) === 3) {
                return $player;
            }
        }
        return "";
    }

    /**
         /$$      /$$                         /$$
        | $$$    /$$$                        | $$
        | $$$$  /$$$$  /$$$$$$  /$$$$$$/$$$$ | $$$$$$$   /$$$$$$   /$$$$$$   /$$$$$$$
        | $$ $$/$$ $$ /$$__  $$| $$_  $$_  $$| $$__  $$ /$$__  $$ /$$__  $$ /$$_____/
        | $$  $$$| $$| $$$$$$$$| $$ \ $$ \ $$| $$  \ $$| $$$$$$$$| $$  \__/|  $$$$$$
        | $$\  $ | $$| $$_____/| $$ | $$ | $$| $$  | $$| $$_____/| $$       \____  $$
        | $$ \/  | $$|  $$$$$$$| $$ | $$ | $$| $$$$$$$/|  $$$$$$$| $$       /$$$$$$$/
        |__/     |__/ \_______/|__/ |__/ |__/|_______/  \_______/|__/      |_______/
     */
    public static function sendInvitation(Player $player, string $faction): void {
        self::$invitations[$player->getName()] = $faction;
        self::$invitationsTimeout[$player->getName()] = time() + self::INVITATION_EXPIRATION_TIME;
    }

    public static function acceptInvitation(Player $player) {
        $name = $player->getName();
        $faction = self::$invitations[$player->getName()];
        $player->faction = $faction;
        $player->faction_role = 0;

        $array = self::$factions[$faction]["players"];
        array_push($array, $name);
        self::$factions[$faction]["players"] = $array;
        foreach (self::getMembers($faction) as $player) {
            if (Server::getInstance()->getPlayer($player)) {
                $player = Server::getInstance()->getPlayer($player);
                if ($player instanceof Player) {
                    $player->sendMessage(Utils::getMessage($player, "FACTION_INVITE_JOIN", ["{INVITED}"], [$name]));
                }
            }
        }
        unset(self::$invitations[$player->getName()]);
        unset(self::$invitationsTimeout[$player->getName()]);
    }

    public static function denyInvitation(Player $player): void {
        $faction = self::$invitations[$player->getName()];
        unset(self::$invitations[$player->getName()]);
        unset(self::$invitationsTimeout[$player->getName()]);

        foreach (self::getMembers($faction) as $member){
            $member = Server::getInstance()->getPlayer($member);
            if(!$member instanceof Player) return;
            Utils::sendMessage($member,"FACTION_INVITE_DENY",["{INVITED}"], [$member->getName()]);
        }
    }

    public static function disbandFaction(String $faction): void {
        foreach (self::$factions[$faction]["players"] as $player){
            $p = Server::getInstance()->getPlayer($player);
            if($p instanceof Player){
                $p->faction = "none";
                $p->faction_role = 0;
            }else{
                Database::update("faction","none",$player);
                Database::update("faction_role",0,$player);
            }
        }

        if (isset(self::$factions[$faction]))unset(self::$factions[$faction]);
    }

    public static function getMembers(String $faction){
        return self::$factions[$faction]["players"];
    }

    public static function isInvited(Player $invited): bool {
        $faction = self::$invitations[$invited->getName()];
        if (count(self::getMembers($faction)) < self::DEFAULT_MAX_MEMBERS) return true; else return false;
    }

    public static function getRoles(String $faction){
        return self::$factions[$faction]["roles"];
    }

    public static function getLeader(string $faction): string {
        foreach (self::getMembers($faction) as $player) {
            if (self::getRoles($faction) === 3) {
                return $player;

    /**
         /$$   /$$
        | $$  | $$
        | $$  | $$  /$$$$$$  /$$$$$$/$$$$   /$$$$$$
        | $$$$$$$$ /$$__  $$| $$_  $$_  $$ /$$__  $$
        | $$__  $$| $$  \ $$| $$ \ $$ \ $$| $$$$$$$$
        | $$  | $$| $$  | $$| $$ | $$ | $$| $$_____/
        | $$  | $$|  $$$$$$/| $$ | $$ | $$|  $$$$$$$
        |__/  |__/ \______/ |__/ |__/ |__/ \_______/
     */


    /**
          /$$$$$$  /$$           /$$
         /$$__  $$| $$          |__/
        | $$  \__/| $$  /$$$$$$  /$$ /$$$$$$/$$$$   /$$$$$$$
        | $$      | $$ |____  $$| $$| $$_  $$_  $$ /$$_____/
        | $$      | $$  /$$$$$$$| $$| $$ \ $$ \ $$|  $$$$$$
        | $$    $$| $$ /$$__  $$| $$| $$ | $$ | $$ \____  $$
        |  $$$$$$/| $$|  $$$$$$$| $$| $$ | $$ | $$ /$$$$$$$/
        \______/ |__/ \_______/|__/|__/ |__/ |__/|_______/
     */

            }
        }
        return "";
    }

    public static function getHome(string $faction) : Position {
        return self::$factions[$faction]["home"] !== null ? unserialize(base64_decode(self::$factions[$faction])) : Utils::getSpawn();
    }

    public static function setHome(string $faction, Position $position) {
        return self::$factions[$faction]["home"] = base64_decode(serialize($position));
    }

    public static function hasHome(string $faction): bool {
        return self::$factions[$faction]["home"] == null;
    }
}