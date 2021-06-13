<?php

namespace Steellg0ld\Museum\custom\items;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\entity\Entity;

class Sword extends TieredTool
{

    public function getBlockToolType() : int{
        return BlockToolType::TYPE_SWORD;
    }

    public function getAttackPoints() : int{
        return self::getBaseDamageFromTier($this->tier);
    }

    public function getBlockToolHarvestLevel() : int{
        return 1;
    }

    public function getMiningEfficiency(Block $block) : float{
        return parent::getMiningEfficiency($block) * 1.5;
    }

    protected function getBaseMiningEfficiency() : float{
        return 10;
    }

    public function onDestroyBlock(Block $block) : bool{
        if($block->getHardness() > 0){
            return $this->applyDamage(2);
        }
        return false;
    }

    public function onAttackEntity(Entity $victim) : bool{
        return $this->applyDamage(1);
    }

}