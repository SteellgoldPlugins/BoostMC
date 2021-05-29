<?php

namespace Steellg0ld\Museum\entity;

use pocketmine\entity\Animal;
use pocketmine\item\Item;

class Wither extends Animal {
    const NETWORK_ID = self::WITHER;

    public $width = 0.72;
    public $length = 6;
    public $height = 2;

    public function getName(): string{
        return "Wither";
    }

    public function register(){
        $this->setMaxHealth(300);
        parent::initEntity();
    }

    public function getDrops(): array{
        return [
            Item::get(Item::NETHER_STAR),
        ];
    }
}