<?php

namespace Steellg0ld\Museum\base;

use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
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

    const MAXIMUM_CLAIMS = 30;

    public const DEFAULT_POWER = 20;
    public const POWER_PER_PLAYER = 10;
    public const DEFAULT_FACTION_PRICE = 500;
    public const POWER_PER_KILL = 5;
    public const POWER_PER_DEATHS = 10;
    public const CLAIM_POWER_COST = 5;
    public const INVITATION_EXPIRATION_TIME = 60;
    public const DEFAULT_MAX_MEMBERS = 10;
    public const TELEPORT_COOLDOWN = 6;
    public const MAX_CHARS_DESCRIPTION = 40;

    /** @var array $map */
    public static array $map = [];

    const MAP_KEY_CHARS = "\\/#?ç¬£$%=&^ABCDEFGHJKLMNOPQRSTUVWXYZÄÖÜÆØÅ1234567890abcdeghjmnopqrsuvwxyÿzäöüæøåâêîûô";
    const MAP_WIDTH = 48;
    const MAP_HEIGHT = 10;
    const MAP_KEY_MIDDLE = TextFormat::AQUA . "+";
    const MAP_KEY_WILDERNESS = TextFormat::GRAY . "-";
    const MAP_KEY_OVERFLOW = TextFormat::WHITE . "-" . TextFormat::RESET;
    const COLOR_ACTIVE = TextFormat::GREEN;
    const COLOR_INACTIVE = TextFormat::RED;


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
            var_dump($player . " 1");
            if (self::getRoles($faction)[$player] == 3) {
                var_dump($player . " 2");
                return $player;
            }
        }
        return "";
    }

    public static function existsFaction(string $faction): bool {
        foreach (self::$factions as $key => $value) {
            if ($key === $faction) {
                return true;
            }
        }
        return false;
    }

    public static function getPower(string $faction, bool $max = false){
        return $max == true ? 5000 : self::$factions[$faction]["power"];
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

    public static function getMembers(String $faction, Bool $count = false){
        return $count == true ? count(self::$factions[$faction]["players"]) : self::$factions[$faction]["players"];
    }

    public static function isInvited(Player $invited): bool {
        $faction = self::$invitations[$invited->getName()];
        if (count(self::getMembers($faction)) < self::DEFAULT_MAX_MEMBERS) return true; else return false;
    }

    public static function kickFaction(string $name): void {
        // TODO (genre)
    }

    public static function leaveFaction(Player $player): void {
        if(self::existsFaction($player->getFaction())){
            if(in_array($player->getName(), self::$factions[$player->getFaction()]["players"])){
                if(!$player->faction_role == 3){
                    unset(self::$factions[$player->getFaction()]["players"][$player->getName()]);
                    Utils::sendMessage($player, "FACTION_LEAVED", ["{FACTION}"],[$player->getFaction()]);
                    $player->faction_role = 0;
                    $player->faction = "none";
                }else{
                    Utils::sendMessage($player,"FACTION_CANT_LEAVE_DISBAND");
                }
            }else{
                $player->sendMessage("cc2");
            }
        }else{
            $player->sendMessage("cc1");
        }
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
        if(self::getPower($faction) >= self::CLAIM_POWER_COST){
            $chunk = $player->getLevel()->getChunkAtPosition($player);
            $chunkX = $chunk->getX();
            $chunkZ = $chunk->getZ();
            $world = $player->getLevel()->getFolderName();
            $claims = self::$claims[$faction];
            array_push($claims, "{$chunkX}:{$chunkZ}:{$world}");
            self::$claims[$faction] = $claims;
            Utils::sendMessage($player, "FACTION_CLAIM");
            self::$factions[$faction]["power"] = self::$factions[$faction]["power"] - 5;
        }else{
            Utils::sendMessage($player,"FACTION_MISSING_POWER_TO_CLAIM");
        }
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

    public static function getMap(Player $player): array {
        $center = $player->getLevel()->getChunkAtPosition($player);
        $height = self::MAP_HEIGHT;
        $width = self::MAP_WIDTH;
        $header = "§7--------------------(§f{X}§7:§f{Z}§7)----------------------";
        $header = str_replace(["{X}", "{Z}"], [$center->getX(), $center->getZ()], $header);
        $map = [$header];
        $legend = [];
        $characterIndex = 0;
        $overflown = false;

        for ($dz = 0; $dz < $height; $dz++) {
            $row = "";
            for ($dx = 0; $dx < $width; $dx++) {
                $chunkX = $center->getX() - ($width / 2) + $dx;
                $chunkZ = $center->getZ() - ($height / 2) + $dz;
                if ($chunkX === $center->getX() && $chunkZ === $center->getZ()) {
                    $row .= self::MAP_KEY_MIDDLE;
                    continue;
                }

                if (self::isInCLaim($player->getLevel(), $chunkX, $chunkZ)) {
                    $faction = self::getFactionClaim($player->getLevel(), $chunkX, $chunkZ);
                    if (($symbol = array_search($faction, $legend)) === false && $overflown) {
                        $row .= self::MAP_KEY_OVERFLOW;
                    } else {
                        if ($symbol === false) $legend[($symbol = self::MAP_KEY_CHARS[$characterIndex++])] = $faction;
                        if ($characterIndex === strlen(self::MAP_KEY_CHARS)) $overflown = true;
                        $row .= self::getMapColor($player, $faction) . $symbol;
                    }
                } else $row .= self::MAP_KEY_WILDERNESS;
            }

            $map[] = $row;
        }

        $map[] = implode(" ", array_map(function (string $character, $faction) use ($player): string {
            return self::getMapColor($player, $faction) . $character . " §f: " . $faction;
        }, array_keys($legend), $legend));
        if ($overflown) $map[] = self::MAP_KEY_OVERFLOW . Utils::getMessage($player, "TOO_MUCH_FACTION");
        return $map;
    }

    public static function getMapColor(Player $player, string $faction1): string {
        if ($player->hasFaction()) {
            $faction2 = $player->getFaction();
            if ($faction1 !== $faction2) {
                if (!self::areAllies($faction1, $faction2)) {
                    return TextFormat::RED;
                } else return TextFormat::YELLOW;
            } else return TextFormat::GREEN;
        } else return TextFormat::RED;
    }

    public static function areAllies(string $faction1, string $faction2): bool {
        return in_array($faction2, self::$factions[$faction1]["allies"]);
    }
}