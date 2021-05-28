<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\item\Item;

class Prices {
    public static array $ores = [
        0 => ["o" => Item::COAL, "b"=>2, "s" => 1, "i" => "textures/items/coal"],
        1 => ["o" => Item::IRON_INGOT,"b"=>2, "s" => 1, "i" => "textures/items/iron_ingot"],
        2 => ["o" => Item::GOLD_INGOT,"b"=>2, "s" => 1, "i" => "textures/items/gold_ingot"],
        3 => ["o" => Item::REDSTONE,"b"=>2, "s" => 1, "i" => "textures/items/redstone_dust"],
        4 => ["o" => Item::EMERALD,"b"=>2, "s" => 1, "i" => "textures/items/emerald"],
        5 => ["o" => Item::DIAMOND,"b"=>2, "s" => 1, "i" => "textures/items/diamond"],
    ];
}