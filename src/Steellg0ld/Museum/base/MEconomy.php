<?php

namespace  Steellg0ld\Museum\base;

use Steellg0ld\Museum\utils\Utils;

class MEconomy {
    public function delMoney(MPlayer  $player, Int $count){
        $player->money = $player->money - $count;
        $player->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}La somme de {PRIMARY}".$count.Utils::ECONOMY_SYMBOL."{SECONDARY}, vous a été retiré(e) vous avez désormais {PRIMARY}".$player->money.Utils::ECONOMY_SYMBOL));
    }
}