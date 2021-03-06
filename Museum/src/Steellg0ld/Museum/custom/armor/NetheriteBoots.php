<?php

namespace Steellg0ld\Museum\custom\armor;

use pocketmine\item\Armor;

class NetheriteBoots extends Armor
{

    const NETHERITE_BOOTS = 751;

    public function __construct(int $meta = 0){
        parent::__construct(self::NETHERITE_BOOTS, $meta, "Netherite Boots");
    }

    public function getDefensePoints() : int{
        return 3;
    }

    public function getMaxDurability() : int{
        return 482;
    }

}