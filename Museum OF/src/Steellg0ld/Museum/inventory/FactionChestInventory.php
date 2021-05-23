<?php

namespace Steellg0ld\Museum\inventory;

use muqsit\invmenu\InvMenu;
use muqsit\invmenu\transaction\InvMenuTransaction;
use muqsit\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\entity\Effect;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\utils\Utils;

class FactionChestInventory {
    public static function openInventory(MPlayer $player, MFaction $faction){
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->send($player, "Coffre de faction");
        $menu->getInventory()->setContents(unserialize(base64_decode($faction->getChest())));
        $menu->setListener(function(InvMenuTransaction $transaction) use ($player) : InvMenuTransactionResult {
            if(($transaction->getItemClicked()->getId() . ":".$transaction->getItemClicked()->getDamage()) === "1000:0"){
                $player->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous ne pouvez pas mettre des items dans cette case, améliorer l'amélioration {ERROR}Stockage "));
                return $transaction->discard();
            }
            return $transaction->continue();
        });

        $menu->setInventoryCloseListener(function () use ($menu, $faction) {
            $faction->updateChest(base64_encode(serialize($menu->getInventory()->getContents())));
        });
    }
}