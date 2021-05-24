<?php

namespace Steellg0ld\Museum;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Steellg0ld\Museum\base\Database;
use Steellg0ld\Museum\commands\defaults\Faction;
use Steellg0ld\Museum\commands\defaults\Settings;
use Steellg0ld\Museum\listeners\player\PlayerListener;
use Steellg0ld\Museum\tasks\async\LoadDatabase;

class Plugin extends PluginBase
{
    public static $instance;
    CONST FILE_DB = "data.db";

    public function onEnable()
    {
        self::$instance = $this;

        @mkdir($this->getDataFolder() . "langs/");
        $this->saveResource("langs/fr_FR.yml");
        $this->saveResource("langs/ch_CH.yml");

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        $this->getDatabase()->initialize();
        $this->loadCommands();
        $this->loadListeners();
        $this->getServer()->getAsyncPool()->submitTask(new LoadDatabase());
    }

    public function onDisable()
    {
        parent::onDisable();
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function loadCommands(){
        $this->getServer()->getCommandMap()->registerAll("museum",[
            new Settings("settings","Configure your game","",["configure","setting"]),
            new Faction("faction","Global faction command","",["f","fac"])
        ]);
    }

    public function loadListeners(){
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
    }

    public function getDatabase(): Database{
        return new Database();
    }

    public function getMessages(String $file): Config
    {
        return new Config($this->getDataFolder() . "langs/".$file.".yml", Config::YAML);
    }
}