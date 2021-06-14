<?php

namespace Steellg0ld\Museum\custom\events;

use pocketmine\event\plugin\PluginEvent;
use pocketmine\Player;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\tiles\SpawnerTile;

class SpawnerEvent extends PluginEvent
{
    /**
     * @var SpawnerTile
     */
    private $spawnerTile;
    /**
     * @var Player
     */
    private $player;

    public function __construct(Player $player, SpawnerTile $spawnerTile)
    {
        parent::__construct(Plugin::getInstance());
        $this->player = $player;
        $this->spawnerTile = $spawnerTile;
    }

    /**
     * @return SpawnerTile
     */
    public function getSpawnerTile(): SpawnerTile
    {
        return $this->spawnerTile;
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }
}