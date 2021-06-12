<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\item\ItemIds;
use Steellg0ld\Museum\Plugin;

class Unicode {
    const GRID = 16;

    public static function init(){
        $config = Plugin::getInstance()->getConfigFile("cc");

        $GRID = 16;
        $filename = basename("glyph_E1", ".png");
        $g = 0;
        $i = 0;
        do {
            $g++;
            var_dump($filename[$g]["identifier"]["minecraft_head_colour"]["1-x3"]++);
        } while (++$i < $GRID ** 2);
    }


    const FOOD = "";
    const SMALL_IRON_CHESTPLATE = "";
    const MINECOIN = "";
    const GOLDEN = "";
    const AUDIO = "";
    const GREEN_SMALL = "";
    const GREEN = "";
    const PLUS = "";
    const ALEX = "";
    const GHOST = "";
    const COW = "";
    const GOLD_COW = "";
    const PERSON = "";
    const IDLE = "";
    const SETTINGS = "";
    const SHIELD = "";
    const ALEX_ONLINE = "";
    const HELP = "";
    const TIMER = "";
    const PAINT = "";
    const SWORD = "";
    const DEATH = "";
    const CRYSTAL = "";
    const CHEST_0 = "";
    const RAGE_FACE = "";
    const MODERATOR_FACE = "";
    const BLACK_MODERATOR_FACE = "";
    const COIN = "";
    const UP = "";
    const BIOLOGIC = "";
    const SEARCH = "";
    const GRASS_FACE = "";
    const RED_BUTTON = "";
    const BLUE_BUTTON = "";
    const YELLOW_BUTTON = "";
    const LIGHT_GREEN_BUTTON = "";
    const PURPLE_BUTTON = "";
    const AQUA_BUTTON = "";
    const ORANGE_BUTTON = "";
    const WHITE_BUTTON = "";
    const ERROR_RED = "";
    const ERROR_BLUE = "";
    const ERROR_YELLOW = "";
    const ERROR_GREEN = "";
    const ERROR_PURPLE = "";
    const ERROR_AQUA = "";
    const ERROR_ORANGE = "";
    const ERROR_GRAY = "";
    const SMALL_RED_BUTTON = "";
    const SMALL_BLUE_BUTTON = "";
    const SMALL_YELLOW_BUTTON = "";
    const SMALL_LIGHT_GREEN_BUTTON = "";
    const SMALL_PURPLE_BUTTON = "";
    const SMALL_LIGHT_BLUE_BUTTON = "";
    const SMALL_ORANGE_BUTTON = "";
    const SMALL_WHITE_BUTTON = "";
    const SMALL_DARK_PURPLE_BUTTON = "";
    const SMALL_DARK_GREEN_BUTTON = "";
    const SMALL_GRAY_BUTTON = "";
    const SMALL_AQUA_BUTTON = "";
    const SMALL_DARK_BUTTON = "";
    const GRAY_BUTTON = "";
    const CLOCK = "";
    const YELLOW_ACCEPT = "";
    const STAR = "";
    const MAGIC_YELLOW = "";
    const MAGIC_PURPLE = "";
    const RED_ACCEPT = "";
    const HEARTH = "";
    const BLUE_ACCEPT = "";
    const PURPLE_ACCEPT = "";
    const BLAZE_POWDER = "";
    const SNOWBALL = "";
    const BONES = "";
    const BUBBLES = "";
    const WOODEN_AXE = "";
    const STONE_AXE = "";
    const IRON_AXE = "";
    const GOLDEN_AXE = "";
    const DIAMOND_AXE = "";
    const NETHERITE_AXE = "";
    const GROUP = "";
    const WHITE_BOW = "";
    const GREEN_BOW = "";
    const PURPLE_BOW = "";
    const ORANGE_BOW = "";
    const BOW = "";
    const WOODEN_PICKAXE = "";
    const STONE_PICKAXE = "";
    const IRON_PICKAXE = "";
    const GOLDEN_PICKAXE = "";
    const DIAMOND_PICKAXE = "";
    const NETHERITE_PICKAXE = "";
    const CACTUS = "";
    const DYNAMITE = "";
    const ANVIL = "";
    const TRIDENT = "";
    const FIREROCK = "";
    const BOXE = "";
    const IDK = "";
    const WOODEN_SWORD = "";
    const STONE_SWORD = "";
    const IRON_SWORD = "";
    const GOLDEN_SWORD = "";
    const DIAMOND_SWORD = "";
    const NETHERITE_SWORD = "";
    const EXCLAMATION = "";
    const MEN_MODERATOR_FACE = "";
    const GIRL_BLACK_MODERATOR_FACE = "";
    const ALEX_OFFLINE = "";
    const DOWN = "";
    const LEFT = "";
    const RIGHT = "";
    const RED_UP = "";
    const RED_DOWN = "";
    const RED_LEFT = "";
    const RED_RIGHT = "";
    const RED_EXCLAMATION = "";
    const RED_HELP = "";
    const PURPLE_BIOLOGIC = "";
    const LESS = "";
    const MEN_ADMIN_FACE = "";
    const MEN_ADMIN_BLACK_FACE = "";
    const GIRL_ADMIN_FACE = "";
    const GIRL_ADMIN_BLACK_FACE = "";
    const MEN_HELPER_FACE = "";
    const MEN_HELPER_BLACK_FACE = "";
    const GIRL_HELPER_FACE = "";
    const GIRL_HELPER_BLACK_FACE = "";
    const TIMER_RIGHT = "";
    const ENDERPEARL_TIMER = "";
    const LEATHER_HELMET = "";
    const LEATHER_CHESTPLATE = "";
    const LEATHER_LEGGINGS = "";
    const LEATHER_BOOTS = "";
    const IRON_HELMET = "";
    const IRON_CHESTPLATE = "";
    const IRON_LEGGINGS = "";
    const IRON_BOOTS = "";
    const CHAINMAIL_HELMET = "";
    const CHAINMAIL_CHESTPLATE = "";
    const CHAINMAIL_LEGGINGS = "";
    const CHAINMAIL_BOOTS = "";
    const GOLDEN_HELMET = "";
    const GOLDEN_CHESTPLATE = "";
    const GOLDEN_LEGGINGS = "";
    const GOLDEN_BOOTS = "";
    const DIAMOND_HELMET = "";
    const DIAMOND_CHESTPLATE = "";
    const DIAMOND_LEGGINGS = "";
    const DIAMOND_BOOTS = "";
    const NETHERITE_HELMET = "";
    const NETHERITE_CHESTPLATE = "";
    const NETHERITE_LEGGINGS = "";
    const NETHERITE_BOOTS = "";

    public static function getMFace(Int $setting, Int $rank): string {
        return [
            5 => [
                0 => self::MEN_HELPER_FACE,
                1 => self::MEN_HELPER_BLACK_FACE,
                2 => self::GIRL_HELPER_FACE,
                3 => self::GIRL_HELPER_BLACK_FACE,
            ],
            6 => [
                0 => self::MEN_MODERATOR_FACE,
                1 => self::BLACK_MODERATOR_FACE,
                2 => self::MODERATOR_FACE,
                3 => self::GIRL_BLACK_MODERATOR_FACE,
            ],
            7 => [
                0 => self::MEN_ADMIN_FACE,
                1 => self::MEN_ADMIN_BLACK_FACE,
                2 => self::GIRL_ADMIN_FACE,
                3 => self::GIRL_ADMIN_BLACK_FACE,
            ],
        ][$rank][$setting];
    }

    CONST ARMORS = [
        ItemIds::LEATHER_HELMET => Unicode::LEATHER_HELMET,
        ItemIds::LEATHER_CHESTPLATE => Unicode::LEATHER_CHESTPLATE,
        ItemIds::LEATHER_LEGGINGS => Unicode::LEATHER_LEGGINGS,
        ItemIds::LEATHER_BOOTS => Unicode::LEATHER_BOOTS,
        ItemIds::CHAIN_HELMET => Unicode::CHAINMAIL_HELMET,
        ItemIds::CHAIN_CHESTPLATE => Unicode::CHAINMAIL_CHESTPLATE,
        ItemIds::CHAIN_LEGGINGS => Unicode::CHAINMAIL_LEGGINGS,
        ItemIds::CHAIN_BOOTS => Unicode::CHAINMAIL_BOOTS,
        ItemIds::IRON_HELMET => Unicode::IRON_HELMET,
        ItemIds::IRON_CHESTPLATE => Unicode::IRON_CHESTPLATE,
        ItemIds::IRON_LEGGINGS => Unicode::IRON_LEGGINGS,
        ItemIds::IRON_BOOTS => Unicode::IRON_BOOTS,
        ItemIds::GOLDEN_HELMET => Unicode::GOLDEN_HELMET,
        ItemIds::GOLDEN_CHESTPLATE => Unicode::GOLDEN_CHESTPLATE,
        ItemIds::GOLDEN_LEGGINGS => Unicode::GOLDEN_LEGGINGS,
        ItemIds::GOLDEN_BOOTS => Unicode::GOLDEN_BOOTS,
        ItemIds::DIAMOND_HELMET => Unicode::DIAMOND_HELMET,
        ItemIds::DIAMOND_CHESTPLATE => Unicode::DIAMOND_CHESTPLATE,
        ItemIds::DIAMOND_LEGGINGS => Unicode::DIAMOND_LEGGINGS,
        ItemIds::DIAMOND_BOOTS => Unicode::DIAMOND_BOOTS
    ];
}