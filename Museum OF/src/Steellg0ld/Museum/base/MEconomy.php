<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\utils\Utils;

class MEconomy
{
    public static $shop = [};
                           
    public function delMoney(MPlayer $player, int $count, bool $msg = false)
    {
        $player->money = $player->money - $count;
        if ($msg == true) $player->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}La somme de {PRIMARY}" . $count . Utils::ECONOMY_SYMBOL . "{SECONDARY}, vous a été retiré(e) vous avez désormais {PRIMARY}" . $player->money . Utils::ECONOMY_SYMBOL));
    }

    public function addMoney(MPlayer $player, int $count, bool $msg = false)
    {
        $player->money = $player->money + $count;
        if ($msg == true) $player->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}La somme de {PRIMARY}" . $count . Utils::ECONOMY_SYMBOL . "{SECONDARY}, vous a été ajouté, vous avez désormais {PRIMARY}" . $player->money . Utils::ECONOMY_SYMBOL));
    }

    public function getItemPrice(MPlayer $player, int $count, bool $msg = false)
    {
        // TODO
    }
}
