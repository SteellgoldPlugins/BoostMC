<?php

namespace Steellg0ld\Museum\entity;

use pocketmine\entity\Monster;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use function mt_rand;

class Zombie extends Monster
{

    public const NETWORK_ID = self::ZOMBIE;

    public $width = 0.6;
    public $height = 1.95;

    public function getName(): string
    {
        return "Zombie";
    }

    public function initEntity(): void
    {
        $this->setMaxHealth(20);
        parent::initEntity();
    }

    public function getDrops(): array
    {
        $lootingL = 1;
        $cause = $this->lastDamageCause;
        if ($cause instanceof EntityDamageByEntityEvent) {
            $dmg = $cause->getDamager();
            if ($dmg instanceof Player) {

                $looting = $dmg->getInventory()->getItemInHand()->getEnchantment(Enchantment::LOOTING);
                if ($looting !== null) {
                    $lootingL = $looting->getLevel();
                } else {
                    $lootingL = 1;
                }
            }
        }
        $drops = [
            ItemFactory::get(Item::ROTTEN_FLESH, 0, mt_rand(0, 2 * $lootingL))
        ];

        if (mt_rand(0, 199) < 5) {
            switch (mt_rand(0, 2)) {
                case 0:
                    $drops[] = ItemFactory::get(Item::IRON_INGOT, 0, 1 * $lootingL);
                    break;
                case 1:
                    $drops[] = ItemFactory::get(Item::CARROT, 0, 1 * $lootingL);
                    break;
                case 2:
                    $drops[] = ItemFactory::get(Item::POTATO, 0, 1 * $lootingL);
                    break;
            }
        }

        return $drops;
    }

    public function getXpDropAmount(): int
    {
        return 5 + mt_rand(1, 3);
    }
}