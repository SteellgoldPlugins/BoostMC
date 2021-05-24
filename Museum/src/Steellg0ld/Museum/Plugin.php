<?php

namespace Steellg0ld\Museum;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use Steellg0ld\Museum\base\Database;
use Steellg0ld\Museum\listeners\player\PlayerListener;
use Steellg0ld\Museum\tasks\LoadItTask;
use Steellg0ld\Museum\utils\Utils;

class Plugin extends PluginBase
{
    public static $instance;
    CONST FILE_DB = "db";

    public function onEnable()
    {
        self::$instance = $this;

        $this->saveResource("lang.yml");
        @mkdir($this->getDataFolder() . "langs/");
        $this->saveResource($this->getDataFolder() . "fr_FR.yml");

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }
        $this->getServer()->getPluginManager()->registerEvents(new PlayerListener(), $this);
    }

    public function onDisable()
    {
        parent::onDisable();
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function getDatabase(): Database{
        return new Database();
    }

    public function getMessages(String $file): Config
    {
        return new Config($this->getDataFolder() . "langs/".$file.".yml", Config::YAML);
    }
}