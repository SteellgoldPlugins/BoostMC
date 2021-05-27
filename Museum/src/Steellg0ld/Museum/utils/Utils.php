<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\Server;
use Steellg0ld\Museum\base\Faction;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\Plugin;

class Utils {
    CONST SEARCH = ["{n}","{PREFIX}", "{PRIMARY}", "{SECONDARY}", "{ERROR}", "{DARK_ERROR}"];
    CONST REPLACE = ["\n","§l» §r§f", "§a", "§f", "§c", "§4"];
    CONST LANGS = [0 => "fr_FR", 1 => "en_EN", 2 => "es_ES", 3 => "it_IT", 4 => "ch_CH"];

    public static function getPrefix(): string{
        return "§l» §r§f";
    }

    public static function sendMessage(Player $player, String $message = "NO_MESSAGE_FOUND", array $search = [], array $replace = [], bool $server = false){
        $total = str_replace($search, $replace,Plugin::getInstance()->getMessages($player->getLang())->exists($message) ? str_replace(self::SEARCH, self::REPLACE, Plugin::getInstance()->getMessages($player->getLang())->get($message)) : "NO_MESSAGE_FOUND");
        if($server) foreach (Server::getInstance()->getOnlinePlayers() as $players) $players->sendMessage($total); else $player->sendMessage(str_replace($search, $replace,Plugin::getInstance()->getMessages($player->getLang())->exists($message) ? str_replace(self::SEARCH, self::REPLACE, Plugin::getInstance()->getMessages($player->getLang())->get($message)) : "NO_MESSAGE_FOUND"));
    }

    public static function getMessage(Player $player, String $message = "NO_MESSAGE_FOUND", array $search = [], array $replace = []){
        return str_replace($search, $replace,Plugin::getInstance()->getMessages($player->getLang())->exists($message) ? str_replace(self::SEARCH, self::REPLACE, Plugin::getInstance()->getMessages($player->getLang())->get($message)) : "NO_MESSAGE_FOUND");
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
                $claims = \SQLite3::escapeString(base64_encode(serialize($claim[$faction])));
                $db->query("INSERT INTO faction (faction, players, power, money, allies, claims) VALUES ('$faction', '$players', '$power', '$money', '$allies', '$claims')");
        }
    }
}