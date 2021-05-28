<?php

namespace Steellg0ld\Museum\forms\shop;

use FormAPI\CustomForm;
use FormAPI\SimpleForm;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use Steellg0ld\Museum\base\Economy;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\base\Ranks;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Prices;
use Steellg0ld\Museum\utils\Utils;

class ShopForm{
    public static function open(Player $player)
    {
        {
            $form = new SimpleForm(
                function (Player $p, $data) {
                    if ($data !== null) {
                        switch ($data){
                            case 3:
                                self::ores($p);
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "SHOP_TITLE_FORM"));
            $form->setContent(Utils::getMessage($player, "SHOP_LABEL_FORM"));
            $form->addButton("Blocs décoratifs",0,"textures/items/painting");
            $form->addButton("Blocs de constructions",0,"textures/items/brick");
            $form->addButton("Outils de combat",0,"textures/items/netherite_sword");
            $form->addButton("Minerais\n".count(Prices::$ores)." disponibles",0,"textures/items/iron_ingot");
            $form->addButton("Objects divers",0,"textures/items/bucket_empty");
            $player->sendForm($form);
        }
    }

    public static function ores(Player $player)
    {
        {
            $array = [];
            foreach (Prices::$ores as $ores){
                array_push($array, $ores);
            }

            $form = new SimpleForm(
                function (Player $p, $data) use ($array) {
                    if ($data !== null) {
                        self::product($p, $data, Item::get($array[$data]["o"]),$array[$data]["b"],$array[$data]["s"]);
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "SHOP_TITLE_FORM"));
            $form->setContent(Utils::getMessage($player, "SHOP_LABEL_FORM"));
            foreach ($array as $item){
                $form->addButton(Item::get($item["o"])->getName() . "\n§a".$item["b"].Economy::SYMBOLS[$player->settings["economy_symbol"]] ." §r- §c".$item['s'],0,$item['i']."$");
            }
            $player->sendForm($form);
        }
    }

    public static function product(Player $player, $place, Item $item, Int $buy_price, Int $sell_price)
    {
        {
            $form = new CustomForm(
                function (Player $p, $data) use ($item, $buy_price, $sell_price, $place) {
                    if ($data !== null) {
                        $price = ($data[1] == true ? $sell_price * $data[2] : $buy_price * $data[2]);
                        if($data[1] == false){
                            if($p->getInventory()->canAddItem(Item::get($item->getId(),$item->getDamage(),$data[2]))){
                                if(Plugin::getInstance()->getEconomyAPI()->delMoney($p,$price)){
                                    $p->getInventory()->addItem(Item::get($item->getId(),$item->getDamage(),$data[2]));
                                    Utils::sendMessage($p, "SHOP_BUYED", ["{ITEM_BUYED}", "{COUNT}", "{PRICE}"], [$item->getName(), $data[2], $price],false);
                                    Prices::$ores[$place]["s"] = Prices::$ores[$place]["s"] - $buy_price;
                                }else{
                                    Utils::sendMessage($p, "SHOP_NO_ENOUGHT_MONEY", ["{NEEDED}"],[$price - $p->money],false);
                                }
                            }else{
                                Utils::sendMessage($p, "SHOP_INVENTORY_FULL", ["{ITEM}","{COUNT}"],[$item->getName(),$data[2]],false);
                            }
                        }else{
                            if($p->getInventory()->contains(Item::get($item->getId(), $item->getDamage(), $data[2]))){
                                Plugin::getInstance()->getEconomyAPI()->addMoney($p,$price);
                                $p->getInventory()->removeItem(Item::get($item->getId(), $item->getDamage(), $data[2]));
                                Utils::sendMessage($p, "SHOP_SELLED", ["{ITEM_SELLED}", "{COUNT}", "{PRICE}"], [$item->getName(), $data[2], $price],false);
                                Prices::$ores[$place]["b"] = (Prices::$ores[$place]["b"] + $price) * 1.6;
                            }else{
                                Utils::sendMessage($p, "SHOP_NOT_HAVE_ITEM", ["{ITEM}", "{COUNT}"], [$item->getName(), $data[2]]);
                            }
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "SHOP_TITLE_FORM"));
            $form->addLabel(str_replace(["{ITEM}","{BUY_PRICE}","{SELL_PRICE}"],[$item->getName(), $buy_price, $sell_price],Utils::getMessage($player, "SHOP_PRODUCT_LABEL_FORM")));
            $form->addToggle(Utils::getMessage($player, "SHOP_PRODUCT_TOGGLE_FORM"));
            $form->addSlider(Utils::getMessage($player, "SHOP_PRODUCT_SLIDER_FORM"),1,128);
            $player->sendForm($form);
        }
    }
}