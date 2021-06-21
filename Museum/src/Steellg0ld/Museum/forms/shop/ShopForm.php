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
use Steellg0ld\Museum\utils\Unicode;
use Steellg0ld\Museum\utils\Utils;

class ShopForm{
    public static function open(Player $player)
    {
        {
            $form = new SimpleForm(
                function (Player $player, $data) {
                    if ($data !== null) {
                        switch ($data){
                            case 0:
                                self::list($player,Prices::$ORES);
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Unicode::NETHERITE_SWORD . " " . Utils::getMessage($player, "SHOP_TITLE_FORM"));
            $form->setContent(Utils::getMessage($player, "SHOP_LABEL_FORM"));
            $form->addButton(Utils::getMessage($player,"SHOP_CATEGORY_2",["{AVAIBLE}"],[count(Prices::$ORES)]),0,"textures/items/iron_ingot");
            $player->sendForm($form);
        }
    }

    public static function list(Player $player, Array $list)
    {
        {
            $form = new SimpleForm(
                function (Player $player, $data) use ($list) {
                    if ($data !== null) {
                        switch ($data){
                            default:
                                self::product($player, $list[$data]);
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Unicode::NETHERITE_SWORD . " " . Utils::getMessage($player, "SHOP_TITLE_FORM"));
            $form->setContent(Utils::getMessage($player, "SHOP_LABEL_FORM"));
            foreach ($list as $item){
                $form->addButton(Prices::getName($item) ."\n".$item["price"]."$",0,$item["image"]);
            }
            $player->sendForm($form);
        }
    }

    public static function product(Player $player, Array $product){
        {
            $form = new CustomForm(
                function (Player $player, $data) use ($product) {
                    if ($data !== null) {
                        Prices::update($product["key"],(int)$data[2],$data[1]);
                    }
                }
            );

            $form->setTitle(Unicode::NETHERITE_SWORD . " " . Utils::getMessage($player, "SHOP_TITLE_FORM"));
            $form->addLabel(Utils::getMessage($player, "SHOP_LABEL_FORM_PRODUCT",["{ITEM}", "{BUY}","{SELL}","{BUY_COUNT}","{SELL_COUNT}","{STATUS}"],[Prices::getName($product),$product["buy"],$product["sell"],Utils::kConverter($product["cBuy"]),Utils::kConverter($product["cSell"]),Prices::getStatus($player, $product,true)]));
            $form->addToggle(Utils::getMessage($player,"SHOP_PRODUCT_TOGGLE_FORM"));
            $form->addInput(Utils::getMessage($player,"SHOP_PRODUCT_SLIDER_FORM"));
            $player->sendForm($form);
        }
    }
}