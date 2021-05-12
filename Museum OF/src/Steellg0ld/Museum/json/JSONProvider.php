<?php

namespace Steellg0ld\Museum\json;

use pocketmine\utils\Config;
use Steellg0ld\Museum\Plugin;

class JSONProvider
{
    public function initialize(): void {
        $dataFolder = Plugin::getInstance()->getDataFolder();
        if(!is_dir($dataFolder . "factions")) {
            mkdir($dataFolder . "factions");
        }

        foreach (Plugin::getInstance()->getConfigFile("factions")->getAll() as $item) {
            var_dump($item);
        }
    }

    public function getFactionConfig(string $factionId): Config {
        return new Config(Plugin::getInstance()->getDataFolder() . "factions/$factionId.json", Config::JSON, []);
    }
}