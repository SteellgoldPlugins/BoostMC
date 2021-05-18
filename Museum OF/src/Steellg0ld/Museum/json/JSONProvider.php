<?php

namespace Steellg0ld\Museum\json;

use pocketmine\utils\Config;
use Steellg0ld\Museum\json\data\DataProvider;
use Steellg0ld\Museum\Plugin;

class JSONProvider
{
    public function initialize(): void {
        $data = new DataProvider();

        $dataFolder = Plugin::getInstance()->getDataFolder();
        if(!is_dir($dataFolder . "factions")) {
            mkdir($dataFolder . "factions");
        }

        foreach (Plugin::getInstance()->getConfigFile("factions")->getAll() as $item) {
            $this->getFactionConfig($item);
            var_dump(Plugin::getInstance()->getFactions()->getFactionConfig($item)->getAll());
        }
    }

    public function getFactionConfig(string $factionId): Config {
        return new Config(Plugin::getInstance()->getDataFolder() . "factions/$factionId.json", Config::JSON, $this->getDataProvider()->defaultData($factionId));
    }

    public function getFactions(): Config {
        return new Config(Plugin::getInstance()->getDataFolder() . "factions.yml", Config::YAML);
    }

    public function getDataProvider(): DataProvider{
        return new DataProvider();
    }
}