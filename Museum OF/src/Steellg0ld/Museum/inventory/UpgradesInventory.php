<?php

namespace Steellg0ld\Museum\inventory;

use muqsit\invmenu\InvMenu;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;

class UpgradesInventory {
    public static function openInventory(MPlayer $player, MFaction $faction){
        $menu = InvMenu::create(InvMenu::TYPE_HOPPER);
        $menu->setName("");

        $menu->send($player, "Améliorations de la faction", function(bool $sent) : void{
            if($sent){
                var_dump("Inventaire envoyé(e)");
            }
        });
    }
}