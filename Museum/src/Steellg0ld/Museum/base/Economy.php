<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\utils\Prices;
use Steellg0ld\Museum\utils\Unicode;

class Economy
{
    public const SYMBOLS = [
        1 => self::UNICODE,
        2 => self::DOL,
        3 => self::EUR,
        4 => self::ARO,
        5 => self::YEN
    ];

    const DOL = "$";
    const EUR = "€";
    const ARO = "@";
    const YEN = "¥";
    const UNICODE = Unicode::COIN;

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

    public function calculate(Int $actual, Int $count, Bool $sell = false): int {
        if($actual <= 0) return $actual + $count;
        if($sell){
            return $actual + $count;
        }else{
            return $actual - $count;
        }
    }
}