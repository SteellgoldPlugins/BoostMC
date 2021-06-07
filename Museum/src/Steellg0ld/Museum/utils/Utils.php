<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\Server;
use Steellg0ld\Museum\base\Economy;
use Steellg0ld\Museum\base\Faction;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\Plugin;

class Utils {
    CONST SEARCH = ["{l}","{n}","{PREFIX}", "{SUCCESS_PREFIX}", "{ERROR_PREFIX}", "{SUCCESS_HELP}", "{ERROR_HELP}", "{+}", "{-}", "{PRIMARY}", "{SECONDARY}", "{TERTIARY}", "{BASE}",  "{ERROR}", "{DARK_ERROR}", "{DEFAULT_FACTION_PRICE}"];
    CONST REPLACE = ["\n","\n","§l» §r§f", Unicode::EXCLAMATION." ", Unicode::RED_EXCLAMATION." ", Unicode::HELP." ", Unicode::RED_HELP." ", Unicode::PLUS." ", Unicode::LESS." ", "§a", "§f", "§7", "§f", "§c", "§4", Faction::DEFAULT_FACTION_PRICE];
    CONST LANGS = [0 => "fr_FR", 1 => "en_EN", 2 => "es_ES", 3 => "it_IT", 4 => "ch_CH"];

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
                $roles = \SQLite3::escapeString(base64_encode($values["roles"]));
                $dates = \SQLite3::escapeString(base64_encode($values["dates"]));
                $invests = \SQLite3::escapeString(base64_encode($values["invests"]));
                $home = \SQLite3::escapeString(base64_encode(serialize($values["home"])));
                $claims = \SQLite3::escapeString(base64_encode(serialize($claim[$faction])));
                $db->query("INSERT INTO faction (faction, players, power, money, allies, description, claim_message,claims,roles,dates,invests,home) VALUES ('$faction', '$players', '$power', '$money', '$allies', '$description', '$claim_message', '$claims', '$roles', '$dates', '$invests', '$home')");
        }
    }

    public static function getSpawn(): Position {
        return new Position(445,4,198,Server::getInstance()->getLevelByName("world"));
    }
}