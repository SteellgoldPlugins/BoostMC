<?php

namespace Steellg0ld\Museum;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
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
}