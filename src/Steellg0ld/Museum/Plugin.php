<?php

namespace Steellg0ld\Museum;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Steellg0ld\Museum\base\Database;

class Plugin extends PluginBase {
    public static $instance;

    public function onEnable(){
        self::$instance = $this;
    }

    /**
     * @return mixed
     */
    public static function getInstance() : Plugin {
        return self::$instance;
    }

    /**
     * @return Config
     */
    public function getFactions() : Config {
        return new Config(Plugin::getInstance()->getDataFolder() . "factions.yml", Config::YAML);
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database{
        return new Database();
    }
}