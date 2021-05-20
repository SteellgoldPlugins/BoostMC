<?php

namespace Steellg0ld\Museum;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Steellg0ld\Museum\api\Claims;
use Steellg0ld\Museum\api\libasynql\DataConnector;
use Steellg0ld\Museum\api\libasynql\libasynql;
use Steellg0ld\Museum\base\Database;
use Steellg0ld\Museum\base\MEconomy;
use Steellg0ld\Museum\commands\CodeCommand;
use Steellg0ld\Museum\commands\faction\FactionCommand;
use Steellg0ld\Museum\commands\HelpCommand;
use Steellg0ld\Museum\json\JSONProvider;
use Steellg0ld\Museum\listeners\FactionListeners;
use Steellg0ld\Museum\listeners\PlayerListeners;

class Plugin extends PluginBase
{
    public static $instance;
    private $database;
    public static $factions;

    public function onEnable()
    {
        self::$instance = $this;

        $this->saveConfig();
        $this->database = libasynql::create($this, $this->getConfigFile("config")->get("database"), [
            "sqlite" => "data.sql",
            "mysql" => "data.sql"
        ]);
        $this->getDatabase()->init();

        $this->getServer()->getCommandMap()->registerAll("museum", [
            new FactionCommand("f", "§aOuvrir la page principale du plugin faction", ["faction"]),
            new CodeCommand("c", "§aOuvrir la page pour utiliser un code", ["code"])
        ]);

        $this->getFactions()->initialize();
        $this->getClaims()->initClaim();
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListeners(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new HelpCommand(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new FactionListeners(), $this);
    }

    public function onDisable()
    {
        if(isset($this->database)) $this->database->close();
    }

    public function getDatabase(): Database
    {
        return new Database();
    }

    public function getAsyncDatabase(): DataConnector
    {
        return $this->database;
    }

    /**
     * @return JSONProvider
     */
    public function getFactions(): JSONProvider
    {
        return new JSONProvider();
    }

    /**
     * @return mixed
     */
    public static function getInstance(): Plugin
    {
        return self::$instance;
    }

    /**
     * @return Config
     */
    public function getCodes(): Config
    {
        return new Config(Plugin::getInstance()->getDataFolder() . "codes.yml", Config::YAML);
    }

    /**
     * @return Claims
     */
    public function getClaims(): Claims
    {
        return new Claims();
    }

    /**
     * @return Config
     */
    public function getClaimsMessages(): Config
    {
        return new Config(Plugin::getInstance()->getDataFolder() . "claims_messages.yml", Config::YAML);
    }

    public function getEconomyAPI(): MEconomy
    {
        return new MEconomy();
    }

    /**
     * @param String $config
     * @return Config
     */
    public function getConfigFile(string $config): Config{
        return new Config($this->getDataFolder() . $config . ".yml", Config::YAML);
    }
}