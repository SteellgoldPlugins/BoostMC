<?php

namespace Steellg0ld\Museum\base;

class Economy
{
    public const SYMBOLS = [
        1 => self::DOL,
        2 => self::EUR,
        3 => self::ARO,
        4 => self::YEN
    ];

    const DOL = "$";
    const EUR = "€";
    const ARO = "@";
    const YEN = "¥";

    public function addMoney(Player $player, Int $count){
        $player->money = $player->money + $count;
    }

    public function delMoney(Player $player, Int $count): bool {
        if($this->haveNeededMoney($player, $count)) return $this->reduceMoney($player, $count); else return false;
    }

    public function haveNeededMoney(Player $player, Int $need): bool {
        return $player->money >= $need;
    }

    private function reduceMoney(Player $player, Int $count): bool {
        $player->money = $player->money - $count;
        return true;
    }
}