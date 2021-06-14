<?php

namespace Steellg0ld\Museum\base;

use pocketmine\item\enchantment\Enchantment as DefaultEnchant;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\utils\TextFormat;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\utils\Utils;

class Enchantment {
    const RARITY_MYTHIC = 10;
    const RARITY_RARE = 5;
    const RARITY_UNCOMMON = 2;
    const RARITY_COMMON = 1;

    const TESTING = 100;

    public function init(){
        DefaultEnchant::registerEnchantment(new DefaultEnchant(self::TESTING,"Testing Enchantment",DefaultEnchant::RARITY_COMMON,DefaultEnchant::SLOT_SWORD,DefaultEnchant::SLOT_NONE,3));
    }

    public static function display(Item $item, Player $player) : Item{
        if(count($item->getEnchantments()) < 1) return $item;
        $enchants = [];
        $cu = TextFormat::RESET . "§fEnchantements spéciaux";
        foreach ($item->getEnchantments() as $enchantment){
            if($enchantment->getId() >= 100){
                if($enchantment instanceof EnchantmentInstance){
                    $enc = DefaultEnchant::getEnchantment($enchantment->getId());
                    if(!in_array($enc,$enchants)) array_push($enchants, $enc);
                }
            }
        }

        foreach ($enchants as $enchant){
            if($enchant instanceof DefaultEnchant)
                if(!strpos($cu,$enchant->getName())) $cu .= "\n".TextFormat::RESET . "§7- ".$enchant->getName() . " " . Utils::getRomanNumeral($item->getEnchantment($enchant->getId())->getLevel());
        }

        unset($enchants);

        $item->setLore([$cu]);
        return $item;
    }

    public static function getColorFromRarity(Int $rarity): string{
        if($rarity >= 10) return TextFormat::WHITE;
        if($rarity >= 5) return TextFormat::GREEN;
        if($rarity >= 2) return TextFormat::GOLD;
        if($rarity >= 1) return TextFormat::AQUA;
    }
}