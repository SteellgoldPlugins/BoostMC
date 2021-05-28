<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\item\Item;

class Prices {
    const ORES = [
        Item::COAL => ["o" => Item::COAL, "b"=>2, "s" => 1, "i" => "textures/items/coal"],
        Item::IRON_INGOT => ["o" => Item::IRON_INGOT,"b"=>2, "s" => 1, "i" => "textures/items/iron_ingot"],
        Item::GOLD_INGOT => ["o" => Item::GOLD_INGOT,"b"=>2, "s" => 1, "i" => "textures/items/gold_ingot"],
        Item::REDSTONE => ["o" => Item::REDSTONE,"b"=>2, "s" => 1, "i" => "textures/items/redstone_dust"],
        Item::EMERALD => ["o" => Item::EMERALD,"b"=>2, "s" => 1, "i" => "textures/items/emerald"],
        Item::DIAMOND => ["o" => Item::DIAMOND,"b"=>2, "s" => 1, "i" => "textures/items/diamond"],
    ];
}