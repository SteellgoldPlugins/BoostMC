<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\entity\EntityIds as PMEIds;

class EntityIds {

    /**
     * @param int $entityID
     * @return string
     */
    public static function getEntityNameFromID(int $entityID): string
    {
        $names = [
            PMEIds::BAT => "Bat",
            PMEIds::BLAZE => "Blaze",
            PMEIds::CAVE_SPIDER => "Cave Spider",
            PMEIds::CHICKEN => "Chicken",
            PMEIds::COW => "Cow",
            PMEIds::CREEPER => "Creeper",
            PMEIds::DOLPHIN => "Dolphin",
            PMEIds::DONKEY => "Donkey",
            PMEIds::ELDER_GUARDIAN => "Elder Guardian",
            PMEIds::ENDERMAN => "Enderman",
            PMEIds::ENDERMITE => "Endermite",
            PMEIds::GHAST => "Ghast",
            PMEIds::GUARDIAN => "Guardian",
            PMEIds::HORSE => "Horse",
            PMEIds::HUSK => "Husk",
            PMEIds::IRON_GOLEM => "Iron Golem",
            PMEIds::LLAMA => "Llama",
            PMEIds::MAGMA_CUBE => "Magma Cube",
            PMEIds::MOOSHROOM => "Mooshroom",
            PMEIds::MULE => "Mule",
            PMEIds::OCELOT => "Ocelot",
            PMEIds::PANDA => "Panda",
            PMEIds::PARROT => "Parrot",
            PMEIds::PHANTOM => "Phantom",
            PMEIds::PIG => "Pig",
            PMEIds::POLAR_BEAR => "Polar Bear",
            PMEIds::RABBIT => "Rabbit",
            PMEIds::SHEEP => "Sheep",
            PMEIds::SHULKER => "Shulker",
            PMEIds::SILVERFISH => "Silverfish",
            PMEIds::SKELETON => "Skeleton",
            PMEIds::SKELETON_HORSE => "Skeleton Horse",
            PMEIds::SLIME => "Slime",
            PMEIds::SNOW_GOLEM => "Snow Golem",
            PMEIds::SPIDER => "Spider",
            PMEIds::SQUID => "Squid",
            PMEIds::STRAY => "Stray",
            PMEIds::VEX => "Vex",
            PMEIds::VILLAGER => "Villager",
            PMEIds::VINDICATOR => "Vindicator",
            PMEIds::WITCH => "Witch",
            PMEIds::WITHER_SKELETON => "Wither Skeleton",
            PMEIds::WOLF => "Wolf",
            PMEIds::ZOMBIE => "Zombie",
            PMEIds::ZOMBIE_HORSE => "Zombie Horse",
            PMEIds::ZOMBIE_PIGMAN => "Zombie Pigman",
            PMEIds::ZOMBIE_VILLAGER => "Zombie Villager",
            121 => "Fox",
            122 => "Bee",
            59 => "Ravager",
            114 => "Pillager",
            130 => "Axolotl",
            128 => "goat"
        ];

        return $names[$entityID] ?? "Monster";
    }

    /**
     * @return array
     */
    public static function getEntityArrayList(): array
    {
        $names = [
            "bat",
            "bee",
            "blaze",
            "cavespider",
            "chicken",
            "cow",
            "creeper",
            "donkey",
            "elderguardian",
            "enderman",
            "endermite",
            "fox",
            "ghast",
            "guardian",
            "horse",
            "husk",
            "irongolem",
            "llama",
            "magmacube",
            "mooshroom",
            "mule",
            "ocelot",
            "panda",
            "parrot",
            "pig",
            "polarbear",
            "rabbit",
            "ravager",
            "sheep",
            "shulker",
            "silverfish",
            "skeleton",
            "slime",
            "snowgolem",
            "spider",
            "squid",
            "stray",
            "vex",
            "villager",
            "vindicator",
            "witch",
            "witherskeleton",
            "wolf",
            "zombie",
            "pigzombie",
            "zombievillager"
        ];
        return $names;
    }

    /**
     * @param string $entityName
     * @return int|null
     */
    public static function getEntityIDFromName(string $entityName): ?int
    {
        $names = [
            "bat" => PMEIds::BAT,
            "bee" => 122,
            "blaze" => PMEIds::BLAZE,
            "cavespider" => PMEIds::CAVE_SPIDER,
            "chicken" => PMEIds::CHICKEN,
            "cow" => PMEIds::COW,
            "creeper" => PMEIds::CREEPER,
            "dolphin" => PMEIds::DOLPHIN,
            "donkey" => PMEIds::DONKEY,
            "elderguardian" => PMEIds::ELDER_GUARDIAN,
            "enderman" => PMEIds::ENDERMAN,
            "endermite" => PMEIds::ENDERMITE,
            "fox" => 121,
            "ghast" => PMEIds::GHAST,
            "guardian" => PMEIds::GUARDIAN,
            "horse" => PMEIds::HORSE,
            "husk" => PMEIds::HUSK,
            "irongolem" => PMEIds::IRON_GOLEM,
            "llama" => PMEIds::LLAMA,
            "magmacube" => PMEIds::MAGMA_CUBE,
            "mooshroom" => PMEIds::MOOSHROOM,
            "mule" => PMEIds::MULE,
            "ocelot" => PMEIds::OCELOT,
            "panda" => PMEIds::PANDA,
            "parrot" => PMEIds::PARROT,
            "phantom" => PMEIds::PHANTOM,
            "pig" => PMEIds::PIG,
            "polarbear" => PMEIds::POLAR_BEAR,
            "rabbit" => PMEIds::RABBIT,
            "ravager" => 59,
            "sheep" => PMEIds::SHEEP,
            "shulker" => PMEIds::SHULKER,
            "silverfish" => PMEIds::SILVERFISH,
            "skeleton" => PMEIds::SKELETON,
            "skeltonhorse" => PMEIds::SKELETON_HORSE,
            "slime" => PMEIds::SLIME,
            "snowgolem" => PMEIds::SNOW_GOLEM,
            "spider" => PMEIds::SPIDER,
            "squid" => PMEIds::SQUID,
            "stray" => PMEIds::STRAY,
            "vex" => PMEIds::VEX,
            "villager" => PMEIds::VILLAGER,
            "vindicator" => PMEIds::VINDICATOR,
            "witch" => PMEIds::WITCH,
            "witherskeleton" => PMEIds::WITHER_SKELETON,
            "wolf" => PMEIds::WOLF,
            "zombie" => PMEIds::ZOMBIE,
            "zombiehorse" => PMEIds::ZOMBIE_HORSE,
            "pigzombie" => PMEIds::ZOMBIE_PIGMAN,
            "zombievillager" => PMEIds::ZOMBIE_VILLAGER,
            "goat" => 128,
            "pillager" => 114,
            "axolotl" => 130,
        ];

        return $names[$entityName] ?? null;
    }

}