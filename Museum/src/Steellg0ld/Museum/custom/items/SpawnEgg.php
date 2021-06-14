<?php

namespace Steellg0ld\Museum\custom\items;

use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\SpawnEgg as PMSpawnEgg;
use pocketmine\math\Vector3;
use pocketmine\Player;
use Steellg0ld\Museum\custom\blocks\SpawnerBlock;

class SpawnEgg extends PMSpawnEgg
{

    /**
     * @param Player $player
     * @param Block $block
     * @param Block $blockClicked
     * @param int $face
     * @param Vector3 $clickVector
     * @return bool
     */
    public function onActivate(Player $player, Block $block, Block $blockClicked, int $face, Vector3 $clickVector): bool
    {
        if ($blockClicked instanceof SpawnerBlock) {
            return false;
        }
        if($player instanceof \Steellg0ld\Museum\base\Player){
            $level = $player->getLevel();
            $nbt = Entity::createBaseNBT($block->add(1, 0, 1), null, lcg_value() * 360, 0);
            $entity = Entity::createEntity($this->meta, $level, $nbt);
            if ($entity instanceof Entity) {
                if (!$player->isCreative()) {
                    $this->count--;
                }
                $entity->spawnToAll();
                return true;
            }
            return true;
        }
    }
}