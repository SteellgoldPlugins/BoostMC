<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use ReflectionException;
use ReflectionProperty;
use Steellg0ld\Museum\Plugin;
use pocketmine\utils\TextFormat as C;

class SpawnerUtils{
    public function getSpawner(string $name, int $amount): Item
    {
        $name = strtolower($name);
        $name = str_replace(" ", "", $name);
        $entityID = EntityIds::getEntityIDFromName($name);

        $nbt = new CompoundTag("", [
            new IntTag("EntityID", (int)$entityID)
        ]);

        $spawner = Item::get(Item::MOB_SPAWNER, 0, $amount, $nbt);
        $spawnerName = EntityIds::getEntityNameFromID((int)$entityID) . " Spawner";
        $spawner->setCustomName("§r" . $spawnerName);

        return $spawner;
    }

    /**
     * @param Entity $entity
     */
    public function exemptEntityFromStacking(Entity $entity): void
    {
        Plugin::getInstance()->exemptedEntities[] = $entity->getId();
    }

    /**
     * @param string $entityName
     */
    public function exemptEntityFromStackingByName(string $entityName): void
    {
        Plugin::getInstance()->exemptedEntities[] = $entityName;
    }

    /**
     * @return array|null
     * @throws ReflectionException
     */
    public function getRegisteredEntities(): ?array
    {
        $reflectionProperty = new ReflectionProperty(Entity::class, 'knownEntities');
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue();
    }
}