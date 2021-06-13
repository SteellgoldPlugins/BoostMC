<?php

namespace Steellg0ld\Museum;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use Steellg0ld\Museum\api\Scoreboard;
use Steellg0ld\Museum\base\Database;
use Steellg0ld\Museum\base\Economy;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\tasks\async\LoadDatabase;
use Steellg0ld\Museum\tasks\UpdateScoreboard;
use Steellg0ld\Museum\utils\Unicode;
use Steellg0ld\Museum\utils\Utils;

class Plugin extends PluginBase
{
    public static $instance;
    CONST FILE_DB = "data.db";

    public function onEnable()
    {
        self::$instance = $this;

        @mkdir($this->getDataFolder() . "langs/");
        $this->saveResource("langs/fr_FR.yml");

        if (!InvMenuHandler::isRegistered()) {
            InvMenuHandler::register($this);
        }

        $manager = new Manager();
        $manager->loadCommands($this);
        $manager->loadEntitys($this);
        $manager->loadItems($this);
        $manager->loadListeners($this);
        $manager->loadRecipes($this);

        $this->getDatabase()->initialize();
        $this->getServer()->getAsyncPool()->submitTask(new LoadDatabase());
        $this->getScheduler()->scheduleRepeatingTask(new UpdateScoreboard(),20);

        Unicode::init();
    }

    public function onDisable() {
        Utils::saveAll();
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            if($player instanceof Player) $this->getDatabase()->player_update($player->getName(),base64_encode(base64_encode(base64_encode($player->getAddress()))),$player->faction,$player->faction_role,$player->rank,$player->money,$player->lang,base64_encode(serialize($player->settings)),$player->discordId);
        }
    }

    public static function getInstance(): self {
        return self::$instance;
    }

    public function getDatabase(): Database{
        return new Database();
    }

    public function getMessages(String $file): Config {
        return new Config($this->getDataFolder() . "langs/".$file.".yml", Config::YAML);
    }

    public function getConfigFile(String $file): Config {
        return new Config($this->getDataFolder() . $file.".yml", Config::YAML);
    }

    public function getEconomyAPI(): Economy{
        return new Economy();
    }

    public function getScoreboardAPI(): Scoreboard {
        return new Scoreboard();
    }
}