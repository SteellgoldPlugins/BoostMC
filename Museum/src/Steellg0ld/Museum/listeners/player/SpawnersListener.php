<?php

namespace Steellg0ld\Museum\listeners\player;

use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\ItemBlock;
use pocketmine\item\Pickaxe;
use pocketmine\nbt\tag\IntTag;
use pocketmine\scheduler\ClosureTask;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\custom\blocks\SpawnerBlock;
use Steellg0ld\Museum\custom\events\SpawnerStackEvent;
use Steellg0ld\Museum\custom\items\SpawnEgg;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\tiles\SpawnerTile;
use Steellg0ld\Museum\utils\EntityIds;
use Steellg0ld\Museum\utils\MobStacker;

class SpawnersListener implements Listener
{
    /**
     * @var Plugin
     */
    private $plugin;

    /**
     * EventListener constructor.
     * @param Plugin $plugin
     */
    public function __construct(Plugin $plugin)
    {
        $this->plugin = $plugin;
    }

    /**
     * @param EntityDamageEvent $event
     */
    public function onDamage(EntityDamageEvent $event): void
    {
        $entity = $event->getEntity();

        if (!$entity instanceof Living || $entity instanceof Human) {
            return;
        }

        if (in_array($entity->getId(), $this->plugin->exemptedEntities)) return;

        $mobStacker = new MobStacker($entity);
        if ($entity->getHealth() - $event->getFinalDamage() <= 0) {
            $cause = null;
            if ($event instanceof EntityDamageByEntityEvent) {
                $player = $event->getDamager();
                if ($player instanceof Player) {
                    $cause = $player;
                }
            }
            if ($mobStacker->removeStack($cause)) {
                $entity->setHealth($entity->getMaxHealth());
                $event->setCancelled(true);
            }
        }
    }

    /**
     * @param EntitySpawnEvent $event
     */
    public function onSpawn(EntitySpawnEvent $event): void
    {
        $entity = $event->getEntity();
        $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function (int $currentTick) use ($entity): void {
            if (!$entity instanceof Living) return;
            if (!in_array(strtolower($entity->getName()), EntityIds::getEntityArrayList())) return;
            if (in_array($entity->getId(), $this->plugin->exemptedEntities)) return;
            if (in_array(EntityIds::getEntityNameFromID($entity->getId()), $this->plugin->exemptedEntities)) return;
            if ($entity->getLevel() === null) return;
            if ($entity->getLevel()->isClosed()) return;
            $disabledWorlds = Plugin::getInstance()->getConfigFile("config")->get("mob-stacking-disabled-worlds");
            if (is_array($disabledWorlds)) {
                if (in_array($entity->getLevel()->getFolderName(), $disabledWorlds)) {
                    return;
                }
            }

            if ($entity instanceof Human or !$entity instanceof Living) return;
            $mobStacker = new Mobstacker($entity);
            $mobStacker->stack();
        }), 1);
    }

    /**
     * @param BlockPlaceEvent $event
     */
    public function onPlaceSpawner(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        $item = $event->getItem();
        $block = $event->getBlock();
        $tiles = $block->getLevel()->getChunkTiles($block->getX() >> 4, $block->getZ() >> 4);

        $disabledWorlds = Plugin::getInstance()->getConfigFile("config")->get("spawner-stacking-disabled-worlds");
        if (is_array($disabledWorlds)) {
            if (in_array($player->getLevel()->getFolderName(), $disabledWorlds)) {
                return;
            }
        }

        foreach ($tiles as $tile) {
            if (!$tile instanceof SpawnerTile) {
                return;
            }
            if ($item->getNamedTag()->hasTag(SpawnerTile::ENTITY_ID, IntTag::class) && $item->getNamedTagEntry("EntityID")->getValue() === $tile->getEntityId()) {
                ($spawnerEvent = new SpawnerStackEvent($player, $tile, 1))->call();
                if ($spawnerEvent->isCancelled()) return;
                $tile->setCount($tile->getCount() + 1);
                $player->getInventory()->setItemInHand($item->setCount($item->getCount() - 1));
                $event->setCancelled();
            }
        }
    }


    /**
     * @param PlayerInteractEvent $event
     * @priority MONITOR
     */
    public function onInteractSpawner(PlayerInteractEvent $event): void
    {
        $item = $event->getItem();
        if ($item instanceof Pickaxe) {
            return;
        }
        if ($item instanceof ItemBlock && !$item->getNamedTag()->hasTag(SpawnerTile::ENTITY_ID, IntTag::class)) {
            return;
        }

        $player = $event->getPlayer();
        $vec3 = $event->getBlock()->asVector3();
        $level = $player->getLevel();
        $tile = $level->getTile($vec3);

        if (!$tile instanceof SpawnerTile) {
            if ($item instanceof SpawnEgg) {
                $item->pop();
            }
            return;
        }


        $nbt = $item->getNamedTag();

        $disabledWorlds = Plugin::getInstance()->getConfigFile("config")->get("spawner-stacking-disabled-worlds");
        if (is_array($disabledWorlds)) {
            if (in_array($level->getFolderName(), $disabledWorlds)) {
                return;
            }
        }


        if ($nbt->hasTag(SpawnerTile::ENTITY_ID, IntTag::class)) {
            if (!$tile instanceof SpawnerTile) {
                return;
            }

            $event->setCancelled(true);
            return;
        }

        $event->setCancelled(true);
    }

    public function onExplode(EntityExplodeEvent $event): void
    {
        $blocks = $event->getBlockList();
        foreach ($blocks as $block) {
            if ($block instanceof SpawnerBlock) {
                $block->explode();
            }
        }
    }

    /**
     * @param EntityDeathEvent $event
     */
    public function onDeath(EntityDeathEvent $event): void
    {
        $entity = $event->getEntity();
        if (in_array($entity->getId(), $this->plugin->exemptedEntities)) {
            $key = array_search($entity->getId(), $this->plugin->exemptedEntities);
            unset($key);
        }
    }
}