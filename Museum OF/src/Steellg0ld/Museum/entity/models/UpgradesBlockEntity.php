<?php

namespace Steellg0ld\Museum\entity\models;

use pocketmine\entity\Skin;
use Steellg0ld\Museum\entity\Floating;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\inventory\FactionChestInventory;
use Steellg0ld\Museum\utils\Resources;

class UpgradesBlockEntity extends Floating {

    public $width = 1;
    public $height = 1.3;

    public function getName(): string{
        return "UpgradesBlock";
    }

    public function onClick(MPlayer $player): void{
        FactionChestInventory::openInventory($player, $player->getFaction());
    }

    public function getCustomSkin() : ?Skin{
        return new Skin("chest",Resources::PNGtoBYTES("chest"),"","geometry.chest",Resources::getGeometry("chest"));
    }
}