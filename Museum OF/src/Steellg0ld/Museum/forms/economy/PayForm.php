<?php

namespace Steellg0ld\Museum\forms\economy;

use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\CustomForm;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\utils\Utils;

class PayForm{
    public static function openClaim(MPlayer $player)
    {
        {
            $form = new CustomForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {

                    }
                }
            );

            $form->setTitle(Form::ECONOMY_TITLE);
            $form->addLabel(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous avez {PRIMARY}{ECONOMY_SYMBOL}"));
            $form->addInput("Texte à afficher à l'entrée dans le claim", $player->getFaction()->getName(), $player->getFaction()->getName());
            $player->sendForm($form);
        }
    }
}