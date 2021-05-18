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
            if(!file_exists($dataFolder . "factions/".$item.".json")){
                $file = $this->getFactionConfig($item);
                $file->set($item,$data->defaultData($item));
                $file->save();
            }else{
                var_dump("existe");
            }
        }
    }

    public function getFactionConfig(string $factionId): Config {
        return new Config(Plugin::getInstance()->getDataFolder() . "factions/$factionId.json", Config::JSON, []);
    }

    public function getDataProvider(): DataProvider{
        return new DataProvider();
    }
}