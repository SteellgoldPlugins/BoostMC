<?php

namespace Steellg0ld\Museum;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Steellg0ld\Museum\base\Database;
use Steellg0ld\Museum\base\MEconomy;
use Steellg0ld\Museum\commands\CodeCommand;
use Steellg0ld\Museum\commands\faction\FactionCommand;
use Steellg0ld\Museum\listeners\PlayerListeners;

class Plugin extends PluginBase {
    public static $instance;

    public function onEnable(){
        self::$instance = $this;
        $this->getDatabase()->init();

        $this->getServer()->getCommandMap()->registerAll("museum", [
            new FactionCommand("f","§aOuvrir la page principale du plugin faction",["faction"]),
            new CodeCommand("c","§aOuvrir la page pour utiliser un code",["code"])
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
     * @return Config
     */
    public function getCodes() : Config {
        return new Config(Plugin::getInstance()->getDataFolder() . "codes.yml", Config::YAML);
    }

    public function getEconomyAPI(): MEconomy{
        return new MEconomy();
    }

    /**
     * @return Database
     */
    public function getDatabase(): Database{
        return new Database();
    }
}