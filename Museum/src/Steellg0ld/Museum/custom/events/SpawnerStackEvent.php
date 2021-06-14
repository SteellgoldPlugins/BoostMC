<?php

namespace Steellg0ld\Museum\custom\events;

use Heisenburger69\BurgerSpawners\Tiles\MobSpawnerTile;
use pocketmine\event\Cancellable;
use pocketmine\Player;
use Steellg0ld\Museum\tiles\SpawnerTile;

class SpawnerStackEvent extends SpawnerEvent implements Cancellable
{
    /** @var int */
    public $count;

    public function __construct(Player $player, SpawnerTile $spawnerTile, int $count)
    {
        $this->count = $count;
        parent::__construct($player, $spawnerTile);
    }

    /**
     * @return int
     */
    public function getCount(): int
    {
        return $this->count;
    }

}