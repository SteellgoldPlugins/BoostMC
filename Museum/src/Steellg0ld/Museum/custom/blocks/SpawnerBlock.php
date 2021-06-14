<?php

namespace Steellg0ld\Museum\custom\blocks;

use pocketmine\block\Block;
use pocketmine\block\MonsterSpawner as PMSpawner;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;
use Steellg0ld\Museum\custom\events\SpawnerBreakEvent;
use Steellg0ld\Museum\custom\events\SpawnerPlaceEvent;
use Steellg0ld\Museum\tiles\SpawnerTile;
use Steellg0ld\Museum\base\Player as CustomPlayer;
use Steellg0ld\Museum\utils\EntityIds;

class SpawnerBlock extends PMSpawner
{

    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onActivate(Item $item, Player $player = null): bool
    {
        if($player instanceof CustomPlayer) {
            if ($item->getId() !== Item::SPAWN_EGG) {
                return false;
            }

            $tile = $this->getLevel()->getTile($this);
            if (!$tile instanceof SpawnerTile) {
                $nbt = new CompoundTag("", [
                        new StringTag(Tile::TAG_ID, Tile::MOB_SPAWNER),
                        new IntTag(Tile::TAG_X, (int)$this->x),
                        new IntTag(Tile::TAG_Y, (int)$this->y),
                        new IntTag(Tile::TAG_Z, (int)$this->z),
                    ]
                );
                /** @var SpawnerTile $tile */
                $tile = Tile::createTile(Tile::MOB_SPAWNER, $this->getLevel(), $nbt);
                $tile->setEntityId($item->getDamage());
                (new SpawnerPlaceEvent($player, $tile))->call();
            }

            return true;
        }
    }

    /**
     * @param Item $item
     * @param Block $blockReplace
     * @param Block $blockClicked
     * @param int $face
     * @param Vector3 $clickVector
     * @param Player|null $player
     * @return bool
     */
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool
    {
        parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);

        $nbt = $item->getNamedTag();
        if ($nbt->hasTag(SpawnerTile::ENTITY_ID, IntTag::class)) {
            $entityId = $nbt->getInt(SpawnerTile::ENTITY_ID);
            $tile = $this->getLevel()->getTile($this);
            if (!is_null($tile)) {
                $this->getLevel()->removeTile($tile);
            }

            if (!$tile instanceof SpawnerTile) {
                $nbt = new CompoundTag("", [
                    new StringTag(Tile::TAG_ID, Tile::MOB_SPAWNER),
                    new IntTag(Tile::TAG_X, (int)$this->x),
                    new IntTag(Tile::TAG_Y, (int)$this->y),
                    new IntTag(Tile::TAG_Z, (int)$this->z)
                ]);

                $tile = Tile::createTile(Tile::MOB_SPAWNER, $this->getLevel(), $nbt);
                if ($tile instanceof SpawnerTile) {
                    $tile->setEntityId($entityId);
                    $scale = 3;
                    $tile->setEntityScale($scale);
                    (new SpawnerPlaceEvent($player, $tile))->call();
                }
            }
        }

        return true;
    }

    /**
     * @param Item $item
     * @return array
     */
    public function getDrops(Item $item): array
    {
        return [];
    }

    /**
     * @param Item $item
     * @return array
     */
    public function getSilkTouchDrops(Item $item): array
    {
        return [];
    }

    /**
     * @param Item $item
     * @param Player|null $player
     * @return bool
     */
    public function onBreak(Item $item, Player $player = null): bool
    {
        $parent = parent::onBreak($item, $player);
        if (!$item->hasEnchantment(Enchantment::SILK_TOUCH)) {
            return $parent;
        }

        $tile = $this->getLevel()->getTile($this->asVector3());
        if ($tile instanceof SpawnerTile) {
            $nbt = new CompoundTag("", [
                new IntTag(SpawnerTile::ENTITY_ID, $tile->getEntityId())
            ]);
            $count = $tile->getCount();
            $spawner = Item::get(Item::MOB_SPAWNER, 0, $count, $nbt);
            $spawner->setCustomName("§f" . EntityIds::getEntityNameFromID((int)$tile->getEntityId()) . " Spawner");
            $this->getLevel()->dropItem($this->add(0.5, 0.5, 0.5), $spawner);

            (new SpawnerBreakEvent($player, $tile))->call();
        }
        return $parent;
    }

    /**
     * @return bool
     */
    public function explode(): bool
    {
        if (mt_rand(0, 100) > 4){
            return false;
        }
        $tile = $this->getLevel()->getTile($this->asVector3());
        if ($tile instanceof SpawnerTile) {
            $nbt = new CompoundTag("", [
                new IntTag(SpawnerTile::ENTITY_ID, $tile->getEntityId())
            ]);
            $count = $tile->getCount();
            $spawner = Item::get(Item::MOB_SPAWNER, 0, $count, $nbt);
            $spawner->setCustomName("§f" . EntityIds::getEntityNameFromID((int)$tile->getEntityId()) . " Spawner");
            $this->getLevel()->dropItem($this->add(0.5, 8, 0.5), $spawner);
            return true;
        }
        return false;
    }
}