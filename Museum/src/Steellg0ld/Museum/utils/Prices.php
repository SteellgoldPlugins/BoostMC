<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use Steellg0ld\Museum\base\Player;

class Prices {
    public static array $ORES = [
        0 => ["id" => ItemIds::COAL, "price" => 64, "buy" => 32, "sell" => 16, "image" => "textures/items/coal", "key" => 0, "cBuy" => 0, "cSell" => 0, "old" => 0, "meta" => 0, "custom_name" => false],
        1 => ["id" => ItemIds::IRON_INGOT, "price" => 64, "buy" => 64, "sell" => 32, "image" => "textures/items/iron_ingot", "key" => 1, "cBuy" => 0, "cSell" => 0, "old" => 1, "meta" => 0, "custom_name" => false],
        2 => ["id" => ItemIds::GOLD_INGOT, "price" => 64, "buy" => 64, "sell" => 32, "image" => "textures/items/gold_ingot", "key" => 2, "cBuy" => 0, "cSell" => 0, "old" => 2, "meta" => 0, "custom_name" => false],
        3 => ["id" => ItemIds::REDSTONE, "price" => 64, "buy" => 64, "sell" => 32, "image" => "textures/items/redstone_dust", "key" => 3, "cBuy" => 0, "cSell" => 0, "old" => 3, "meta" => 0, "custom_name" => false],
        4 => ["id" => ItemIds::DYE, "price" => 64, "buy" => 64, "sell" => 32, "image" => "textures/items/dye_powder_blue", "key" => 4, "cBuy" => 0, "cSell" => 0, "old" => 4, "meta" => 4, "custom_name" => "Lapis-Lazuli"],
        5 => ["id" => ItemIds::DIAMOND, "price" => 64, "buy" => 64, "sell" => 32, "image" => "textures/items/diamond", "key" => 5, "cBuy" => 0, "cSell" => 0, "old" => 5, "meta" => 0, "custom_name" => false],
        6 => ["id" => ItemIds::EMERALD, "price" => 64, "buy" => 64, "sell" => 32, "image" => "textures/items/emerald", "key" => 6, "cBuy" => 0, "cSell" => 0, "old" => 6, "meta" => 0, "custom_name" => false]
    ];

    public static function getName(array $item) {
        return $item["custom_name"] == false ? Item::get($item["id"],$item["id"] == 0 ? 0 : $item["id"])->getName():$item["custom_name"];
    }

    public static function getStatus(Player $player, array $item, bool $asText = false) : string {
        $unicode = $item["price"] > $item["old"] ? " ".Unicode::UP : " ".Unicode::RED_DOWN;;
        $text = $item["price"] > $item["old"] ? Utils::getMessage($player,"PRODUCT_STATUS_UP") . Unicode::UP : Utils::getMessage($player,"PRODUCT_STATUS_DOWN") . Unicode::RED_DOWN;
        return $asText ? $text : $unicode;
    }

    public static function update($key, $count, bool $sell){
        if($sell){ // vente
            self::$ORES[$key]["price"] = self::$ORES[$key]["price"] + $count;
            self::$ORES[$key]["cSell"] += $count;
        } else { // achat
            if((self::$ORES[$key]["price"] - $count) < 0){
                self::$ORES[$key]["price"] = 1;
            }else{
                self::$ORES[$key]["price"] = self::$ORES[$key]["price"] - $count;
            }
            self::$ORES[$key]["cBuy"] += $count;
        }

        self::$ORES[$key]["old"] = self::$ORES[$key]["price"];
        self::$ORES[$key]["old"] = self::$ORES[$key]["price"];
    }
}