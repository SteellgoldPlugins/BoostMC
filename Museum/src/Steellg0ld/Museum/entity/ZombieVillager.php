<?php

namespace Steellg0ld\Museum\entity;

use pocketmine\entity\Zombie;
use function mt_rand;

class ZombieVillager extends Zombie
{

    public const NETWORK_ID = self::ZOMBIE_VILLAGER;

    public $width = 0.6;
    public $height = 1.95;

    public function getName(): string
    {
        return "Zombie Villager";
    }

    public function initEntity(): void
    {
        $this->setMaxHealth(20);
        parent::initEntity();
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}