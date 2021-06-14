<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\item\Item;
use pocketmine\nbt\tag\IntTag;
use pocketmine\Server;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\Plugin;
use pocketmine\utils\TextFormat as C;

class MobStacker
{

    /** @var string */
    CONST STACK = 'stack';
    /* @var Living */
    private $entity;

    /**
     * Mobstacker constructor.
     * @param Living $entity
     */
    public function __construct(Living $entity)
    {
        $this->entity = $entity;
    }

    public function stack(): void
    {
        if ($this->isStacked()) {
            $this->updateNameTag();
            return;
        }
        if (($mob = $this->findNearStack()) == null) {
            $nbt = new IntTag(self::STACK, 1);
            $this->entity->namedtag->setTag($nbt);
            $mobstack = $this;
        } else {
            $this->entity->flagForDespawn();
            $mobstack = new MobStacker($mob);
            $count = $mob->namedtag->getInt(self::STACK);
            $mob->namedtag->setInt(self::STACK, ++$count);
        }
        $mobstack->updateNameTag();
    }

    /**
     * @return bool
     */
    public function isStacked(): bool
    {
        return $this->entity->namedtag->hasTag(self::STACK);
    }

    public function updateNameTag(): void
    {
        $nbt = $this->entity->namedtag;
        $this->entity->setNameTagAlwaysVisible(True);
        $this->entity->setNameTag(C::BOLD . C::AQUA . $nbt->getInt(self::STACK) . 'x ' . C::BOLD . C::GOLD . $this->entity->getName());
    }

    /**
     * @param int $range
     * @return Living|null
     */
    public function findNearStack(int $range = 16): ?Living
    {
        $entity = $this->entity;
        if ($entity->isFlaggedForDespawn() or $entity->isClosed()) return null;
        $boundingBox = $entity->getBoundingBox()->expandedCopy($range, $range, $range);
        foreach ($entity->getLevel()->getNearbyEntities($boundingBox) as $e) {
            if (!$e instanceof Player and $e instanceof Living) {
                if ($e->distance($entity) <= $range and $e->getName() === $entity->getName() && !in_array($entity->getId(), Plugin::$instance->exemptedEntities)) {
                    $ae = new Mobstacker($e);
                    if ($ae->isStacked() and !$this->isStacked()) return $e;
                }
            }
        }
        return null;
    }

    /**
     * @param Player|null $player
     * @return bool
     */
    public function removeStack(Player $player = null): bool {
        $entity = $this->entity;
        $nbt = $entity->namedtag;
        if (!$this->isStacked() or ($c = $this->getStackAmount()) <= 1) {
            return false;
        }

        $nbt->setInt(self::STACK, --$c);
        $event = new EntityDeathEvent($entity, $drops = $this->getDrops($entity));
        $event->call();
        $this->updateNameTag();

        foreach ($drops as $drop) {
            if (Server::getInstance()->getTicksPerSecond() <= 10) {
                $player->getInventory()->addItem($drop);
            } else {
                $entity->getLevel()->dropItem($entity->getPosition(), $drop);
            }
        }

        $exp = $entity->getXpDropAmount();
        if ($exp > 0) {
            if (Server::getInstance()->getTicksPerSecond() <= 10) {
                $player->addXp($exp);
            } else {
                $entity->getLevel()->dropExperience($entity->asVector3(), $exp);
            }
        }
        return true;
    }

    /**
     * @return int
     */
    public function getStackAmount(): int
    {
        return $this->entity->namedtag->getInt(self::STACK);
    }

    /**
     * @param Living $entity
     * @return bool
     */
    public function hasCustomDrops(Living $entity): bool
    {
        return isset(Plugin::getInstance()->getConfigFile("config")->get("custom-mob-drops")[strtolower($entity->getName())]);
    }

    /**
     * @param Living $entity
     * @return array
     */
    public function getDrops(Living $entity): array
    {
        if ($this->hasCustomDrops($entity)) {
            $dropData = Plugin::getInstance()->getConfigFile("config")->get("custom-mob-drops")[strtolower($entity->getName())];
            $drops = [];
            foreach ($dropData as $dropString) {
                $drops[] = Item::fromString($dropString);
            }
            return $drops;
        } else {
            return $entity->getDrops();
        }
    }

}