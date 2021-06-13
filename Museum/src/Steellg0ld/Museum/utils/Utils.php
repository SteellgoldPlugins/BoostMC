<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\item\ItemIds;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Server;
use Steellg0ld\Museum\base\Economy;
use Steellg0ld\Museum\base\Faction;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\custom\armor\NetheriteBoots;
use Steellg0ld\Museum\custom\armor\NetheriteChestplate;
use Steellg0ld\Museum\custom\armor\NetheriteHelmet;
use Steellg0ld\Museum\custom\armor\NetheriteLeggings;
use Steellg0ld\Museum\Plugin;

class Utils {
    CONST SEARCH = ["{l}","{n}","{PREFIX}", "{SUCCESS_PREFIX}", "{ERROR_PREFIX}", "{SUCCESS_HELP}", "{ERROR_HELP}", "{+}", "{-}", "{PRIMARY}", "{SECONDARY}", "{TERTIARY}", "{BASE}",  "{ERROR}", "{DARK_ERROR}", "{DEFAULT_FACTION_PRICE}"];
    CONST REPLACE = ["\n","\n","§l» §r§f", Unicode::EXCLAMATION." ", Unicode::RED_EXCLAMATION." ", Unicode::HELP." ", Unicode::RED_HELP." ", Unicode::PLUS." ", Unicode::LESS." ", "§a", "§f", "§7", "§f", "§c", "§4", Faction::DEFAULT_FACTION_PRICE];
    CONST LANGS = [0 => "fr_FR", 1 => "en_EN", 2 => "es_ES", 3 => "it_IT", 4 => "ch_CH"];

    CONST HELMET = [ItemIds::LEATHER_HELMET => 55, ItemIds::CHAIN_HELMET => 165, ItemIds::IRON_HELMET => 165, ItemIds::GOLDEN_HELMET => 77, ItemIds::DIAMOND_HELMET => 363, NetheriteHelmet::NETHERITE_HELMET => 407];
    CONST CHESTPLATES = [ItemIds::LEATHER_CHESTPLATE => 80, ItemIds::CHAIN_CHESTPLATE => 240, ItemIds::IRON_CHESTPLATE => 240, ItemIds::GOLDEN_CHESTPLATE => 112, ItemIds::DIAMOND_CHESTPLATE => 528, NetheriteChestplate::NETHERITE_CHESTPLATE => 592];
    CONST LEGGINGS = [ItemIds::LEATHER_LEGGINGS => 75, ItemIds::CHAIN_LEGGINGS => 225, ItemIds::IRON_LEGGINGS => 225, ItemIds::GOLDEN_LEGGINGS => 105, ItemIds::DIAMOND_LEGGINGS => 495, NetheriteLeggings::NETHERITE_LEGGINGS => 555];
    CONST BOOTS = [ItemIds::LEATHER_BOOTS => 65, ItemIds::CHAIN_BOOTS => 195, ItemIds::IRON_BOOTS => 195, ItemIds::GOLDEN_BOOTS => 91, ItemIds::DIAMOND_BOOTS => 429, NetheriteBoots::NETHERITE_BOOTS => 481];

    public static function getPrefix(): string{
        return "§l» §r§f";
    }

    public static function sendMessage(Player $player, String $message = "NO_MESSAGE_FOUND", array $search = [], array $replace = [], bool $server = false, bool $tip = false){
        $total = str_replace(["{ECONOMY_SYMBOL}", "{MONEY}"],[Economy::SYMBOLS[$player->settings["economy_symbol"]], $player->money],str_replace($search, $replace,Plugin::getInstance()->getMessages($player->getLang())->exists($message) ? str_replace(self::SEARCH, self::REPLACE, Plugin::getInstance()->getMessages($player->getLang())->get($message)) : "NO_MESSAGE_FOUND"));
        if($server) foreach (Server::getInstance()->getOnlinePlayers() as $players) $tip ? $players->sendTip($total) : $players->sendMessage($total); else $tip ? $player->sendTip($total) : $player->sendMessage($total);
    }

    public static function getMessage(Player $player, String $message = "NO_MESSAGE_FOUND", array $search = [], array $replace = []){
        return str_replace(["{ECONOMY_SYMBOL}", "{MONEY}"],[Economy::SYMBOLS[$player->settings["economy_symbol"]], $player->money],str_replace($search, $replace,Plugin::getInstance()->getMessages($player->getLang())->exists($message) ? str_replace(self::SEARCH, self::REPLACE, Plugin::getInstance()->getMessages($player->getLang())->get($message)) : "NO_MESSAGE_FOUND"));
    }

    public static function saveAll(): void {
        $db = new \SQLite3(Plugin::getInstance()->getDataFolder() . Plugin::FILE_DB);
        $db->query("DELETE FROM faction");

        $faction = Faction::$factions;
        $claim = Faction::$claims;

        foreach ($faction as $name => $values) {
                $faction = \SQLite3::escapeString($name);
                $players = \SQLite3::escapeString(base64_encode(serialize($values["players"])));
                $power = \SQLite3::escapeString($values["power"]);
                $money = \SQLite3::escapeString($values["money"]);
                $allies = \SQLite3::escapeString(base64_encode(serialize($values["allies"])));
                $description = \SQLite3::escapeString(base64_encode($values["description"]));
                $claim_message = \SQLite3::escapeString(base64_encode($values["claim_message"]));
                $roles = \SQLite3::escapeString(base64_encode(serialize($values["roles"])));
                $dates = \SQLite3::escapeString(base64_encode(serialize($values["dates"])));
                $invests = \SQLite3::escapeString(base64_encode(serialize($values["invests"])));
                $home = \SQLite3::escapeString(base64_encode(serialize($values["home"])));
                $claims = \SQLite3::escapeString(base64_encode(serialize($claim[$faction])));
                $db->query("INSERT INTO faction (faction, players, power, money, allies, description, claim_message,claims,roles,dates,invests,home) VALUES ('$faction', '$players', '$power', '$money', '$allies', '$description', '$claim_message', '$claims', '$roles', '$dates', '$invests', '$home')");
        }
    }

    public static function getSpawn(): Position {
        return new Position(312,4,180,Server::getInstance()->getLevelByName("world"));
    }
}