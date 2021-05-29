<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\item\Item;

class Prices {
    CONST DECORATIVES = 0;
    CONST CONSTRUCTION = 1;
    CONST ORES = 2;
    CONST MISC = 3;

    public static array $decoratives = [];
    public static array $constructions = [];
    public static array $ores = [
        0 => ["o" => Item::COAL, "b"=>2, "s" => 1, "i" => "textures/items/coal"],
        1 => ["o" => Item::IRON_INGOT,"b"=>2, "s" => 1, "i" => "textures/items/iron_ingot"],
        2 => ["o" => Item::GOLD_INGOT,"b"=>2, "s" => 1, "i" => "textures/items/gold_ingot"],
        3 => ["o" => Item::REDSTONE,"b"=>2, "s" => 1, "i" => "textures/items/redstone_dust"],
        4 => ["o" => Item::EMERALD,"b"=>2, "s" => 1, "i" => "textures/items/emerald"],
        5 => ["o" => Item::DIAMOND,"b"=>2, "s" => 1, "i" => "textures/items/diamond"],
    ];

    public static array $misc = [
        0 => ["o" => Item::ENDER_PEARL, "b" => 300, "s" => 20, "i" => "textures/items/ender_pearl"],
        1 => ["o" => Item::GOLDEN_APPLE, "b" => 500, "s" => 0, "i" => "textures/items/apple_golden"],
        2 => ["o" => Item::BONE, "b" => 50, "s" => 25, "i" => "textures/items/bone"],
        3 => ["o" => Item::BOTTLE_O_ENCHANTING, "b" => 450, "s" => 100, "i" => "textures/items/experience_bottle"],
        4 => ["o" => Item::STEAK, "b" => 10, "s" => 0, "i" => "textures/items/beef_cooked"]
    ];

    public static function update(Int $type, Int $id, String $action, Int $new){
        switch ($type){
            case self::DECORATIVES:
                self::$decoratives[$id][$action] = $new;
                break;
            case self::CONSTRUCTION:
                self::$constructions[$id][$action] = $new;
                break;
            case self::ORES:
                self::$ores[$id][$action] = $new;
                break;
            case self::MISC:
                self::$misc[$id][$action] = $new;
                break;
        }
    }
}