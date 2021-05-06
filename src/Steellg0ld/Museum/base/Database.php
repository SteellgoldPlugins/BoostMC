<?php

namespace Steellg0ld\Museum\base;


use pocketmine\Server;
use Steellg0ld\Museum\Plugin;

class Database{
    public function getDatabase(): \SQLite3{
        return new \SQLite3(Plugin::getInstance()->getDataFolder() . "Database.db");
    }

    public function init(){
        
    }
}