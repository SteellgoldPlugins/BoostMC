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
            $form->addButton("Minerais\n".count(Prices::ORES)." disponibles",0,"textures/items/iron_ingot");
            $form->addButton("Objects divers",0,"textures/items/bucket_empty");
            $player->sendForm($form);
        }
    }
}