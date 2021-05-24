<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\Plugin;

class Database
{
    public function getDatabase(): \SQLite3{
        return new \SQLite3(Plugin::getInstance()->getDataFolder() . Plugin::FILE_DB);
    }
}