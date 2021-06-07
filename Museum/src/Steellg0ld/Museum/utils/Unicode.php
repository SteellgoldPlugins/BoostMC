<?php

namespace Steellg0ld\Museum\utils;

use Steellg0ld\Museum\Plugin;

class Unicode {
    const GRID = 16;

    public static function init(){
        $config = Plugin::getInstance()->getConfigFile("cc");

        $GRID = 16;
        $filename = basename("glyph_E1", ".png");
        $startChar = hexdec(substr($filename, strrpos($filename, "_") + 1) . "00");
        $g = 0;
        $i = 0;
        do {
            $g++;
            $ci = $startChar + $i;//char index
            $char = mb_chr($ci);
            $vv[$g] = $char;
        } while (++$i < $GRID ** 2);

        for ($a = 1; ; $a++) {
            if ($a > 121) {
                break;
            }
            $config->set($a, $vv[$a]);
            $config->save();
        }
    }


    const FOOD = "";
    const IRON_CHESTPLATE = "";
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
    const POLICE_FACE = "";
    const BLACK_POLICE_FACE = "";
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
    const MEN_POLICE_FACE = "";
    const GIRL_BLACK_POLICE_FACE = "";
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
}