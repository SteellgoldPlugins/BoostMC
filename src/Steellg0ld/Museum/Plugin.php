<?php

namespace Steellg0ld\Museum;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Steellg0ld\Museum\base\Database;
use Steellg0ld\Museum\commands\faction\FactionCommand;
use Steellg0ld\Museum\listeners\PlayerListeners;
use Steellg0ld\Museum\utils\Utils;

class Plugin extends PluginBase {
    public static $instance;

    public function onEnable(){
        self::$instance = $this;
        $this->getDatabase()->init();

        $this->getServer()->getCommandMap()->registerAll("museum", [
            new FactionCommand("f","§aOuvrir la page principale du plugin faction",["faction"])
        ]);

        $this->getServer()->getPluginManager()->registerEvents(new PlayerListeners(), $this);
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