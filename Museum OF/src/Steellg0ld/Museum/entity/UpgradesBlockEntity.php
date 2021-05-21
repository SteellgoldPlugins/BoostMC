<?php

namespace Steellg0ld\Museum\entity;

use pocketmine\entity\Skin;
use Steellg0ld\Museum\base\MFloating;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\inventory\UpgradesInventory;
use Steellg0ld\Museum\utils\Resources;

class UpgradesBlockEntity extends MFloating {

    public $width = 1;
    public $height = 1.3;

    public function getName(): string{
        return "UpgradesBlock";
    }

    public function onClick(MPlayer $player): void{
        UpgradesInventory::openInventory($player, $player->getFaction());
    }

    public function getCustomSkin() : ?Skin{
        return new Skin("test",Resources::PNGtoBYTES("test"),"","geometry.test",Resources::getGeometry("test"));
    }
}