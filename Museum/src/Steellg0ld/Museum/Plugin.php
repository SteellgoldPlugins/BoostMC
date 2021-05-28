<?php

namespace Steellg0ld\Museum;

use muqsit\invmenu\InvMenuHandler;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use Steellg0ld\Museum\base\Database;
use Steellg0ld\Museum\base\Economy;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\commands\defaults\Faction;
use Steellg0ld\Museum\commands\defaults\Money;
use Steellg0ld\Museum\commands\defaults\Rank;
use Steellg0ld\Museum\commands\defaults\Settings;
use Steellg0ld\Museum\commands\defaults\Shop;
use Steellg0ld\Museum\listeners\player\PlayerListener;
use Steellg0ld\Museum\tasks\async\LoadDatabase;
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
        Utils::saveAll();
        foreach (Server::getInstance()->getOnlinePlayers() as $player){
            if($player instanceof Player) $this->getDatabase()->player_update($player->getName(),base64_encode(base64_encode(base64_encode($player->getAddress()))),$player->faction,$player->faction_role,$player->rank,$player->money,$player->lang,base64_encode(serialize($player->settings)),$player->discordId);
        }
    }

    public static function getInstance(): self
    {
        return self::$instance;
    }

    public function loadCommands(){
        $this->getServer()->getCommandMap()->registerAll("museum",[
            new Settings("settings","Configure your game","",["configure","setting"]),
            new Settings("settings","Configure your game","",["configure","setting"]),
            new Rank("setrank","Set rank to yourself",""),
            new Shop("shop","Buy a item simply",""),
            new Money("money","Edit the money","")
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

    public function getConfigFile(String $file): Config
    {
        return new Config($this->getDataFolder() . $file.".yml", Config::YAML);
    }

    public function getEconomyAPI(): Economy{
        return new Economy();
    }
}