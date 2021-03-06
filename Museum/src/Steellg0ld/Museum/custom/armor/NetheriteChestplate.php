<?php

namespace Steellg0ld\Museum\custom\armor;

use pocketmine\item\Armor;

class NetheriteChestplate extends Armor
{

    const NETHERITE_CHESTPLATE = 749;

    public function __construct(int $meta = 0){
        parent::__construct(self::NETHERITE_CHESTPLATE, $meta, "Netherite Chestplate");
    }

    public function getDefensePoints() : int{
        return 8;
    }

    public function getMaxDurability() : int{
        return 593;
    }

}