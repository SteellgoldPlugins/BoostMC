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

    public static function getMembers(String $faction){
        return self::$factions[$faction]["players"];
    }

    public static function isInvited(Player $invited): bool {
        $faction = self::$invitations[$invited->getName()];
        if (count(self::getMembers($faction)) < self::DEFAULT_MAX_MEMBERS) return true; else return false;
    }

    public static function kickFaction(string $name): void {
        // TODO (genre)
    }

    public static function leaveFaction(Player $player): void {
        // TODO (genre)
    }

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

    public static function getHome(string $faction) {
        var_dump(self::$factions[$faction]["home"]);
        return self::$factions[$faction]["home"] !== "none" ? explode(":", self::$factions[$faction]["home"]) : Utils::getSpawn();
    }

    public static function setHome(string $faction, Position $position): string {
        return self::$factions[$faction]["home"] = $position->getX(). ":".$position->getY().":".$position->getZ();
    }

    public static function hasHome(string $faction): bool {
        return self::$factions[$faction]["home"] !== "none";
    }

    public static function teleportHome(Player $sender) {
        if(self::hasHome($sender->getFaction())){
            $home = self::getHome($sender->getFaction());
            Plugin::getInstance()->getScheduler()->scheduleRepeatingTask(new CooldownTeleportTask($sender,self::TELEPORT_COOLDOWN, new Vector3($home[0], $home[1], $home[2]), ["x" => $sender->getX(), "y" => $sender->getY(), "z" => $sender->getZ()]),20);
        }else{
            Utils::sendMessage($sender,"FACTION_NO_HOME");
        }
    }

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

    public static function getClaimCount(string $faction): int {
        return count(self::$claims[$faction]) ?? 0;
    }

    public static function isInClaim(Level $level, int $chunkX, int $chunkZ): bool {
        $world = $level->getFolderName();
        $array = [];
        foreach (self::$claims as $faction => $claims) {
            $array = array_merge($array, $claims);
        }
        return in_array("{$chunkX}:{$chunkZ}:{$world}", $array);
    }

    public static function getFactionClaim(Level $level, int $chunkX, int $chunkZ): string {
        $world = $level->getFolderName();
        foreach (self::$claims as $faction => $claims) {
            foreach ($claims as $claim) {
                if ($claim === "{$chunkX}:{$chunkZ}:{$world}") return $faction;
            }
        }
        return "";
    }

    public static function claimChunk(Player $player, string $faction): void {
        $chunk = $player->getLevel()->getChunkAtPosition($player);
        $chunkX = $chunk->getX();
        $chunkZ = $chunk->getZ();
        $world = $player->getLevel()->getFolderName();
        $claims = self::$claims[$faction];
        array_push($claims, "{$chunkX}:{$chunkZ}:{$world}");
        self::$claims[$faction] = $claims;
    }

    public static function deleteClaim(Player $player, string $faction) {
        $chunk = $player->getLevel()->getChunkAtPosition($player);
        $chunkX = $chunk->getX();
        $chunkZ = $chunk->getZ();
        $world = $player->getLevel()->getFolderName();
        $claim = self::$claims[$faction];
        unset($claim[array_search("{$chunkX}:{$chunkZ}:{$world}", $claim)]);
        self::$claims[$faction] = $claim;
    }

    public static function claim(Player $sender){
        if($sender->faction_role >= self::OFFICIER){
            $chunk = $sender->getLevel()->getChunkAtPosition($sender);
            $chunkX = $chunk->getX();
            $chunkZ = $chunk->getZ();

            if(!self::isInClaim($sender->getLevel(),$chunkX,$chunkZ)){
                self::claimChunk($sender,$sender->getFaction());
                Utils::sendMessage($sender, "FACTION_CLAIM");
            }else{
                Utils::sendMessage($sender, "FACTION_ZONE_ALREADY", ["{FACTION}"], [self::getFactionClaim($sender->getLevel(),$chunkX, $chunkZ)]);
            }
        }else{
            Utils::sendMessage($sender, "MUST_BE_OFFICIER");
        }
    }

    public static function unclaim(Player $sender) {
        if($sender->faction_role >= self::OFFICIER){
            if(self::isInClaim($sender->getLevel(), $sender->getLevel()->getChunkAtPosition($sender)->getX(), $sender->getLevel()->getChunkAtPosition($sender)->getZ())){
                if(self::getFactionClaim($sender->getLevel(), $sender->getLevel()->getChunkAtPosition($sender)->getX(), $sender->getLevel()->getChunkAtPosition($sender)->getZ()) == $sender->getFaction()){
                    self::deleteClaim($sender,$sender->getFaction());
                    Utils::sendMessage($sender, "FACTION_UNCLAIM");
                }else{
                    Utils::sendMessage($sender,"FACTION_ZONE_OUR");
                }
            }else{
                Utils::sendMessage($sender,"NO_CLAIM_ZONE");
            }
        }else{
            Utils::sendMessage($sender, "MUST_BE_OFFICIER");
        }
    }
}