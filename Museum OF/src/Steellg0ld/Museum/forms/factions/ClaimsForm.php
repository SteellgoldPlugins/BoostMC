<?php

namespace Steellg0ld\Museum\forms\factions;

use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\CustomForm;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class ClaimsForm
{
    public static function openClaim(MPlayer $player)
    {
        {
            $form = new CustomForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        if ($p->faction_role >= 2) {
                            Plugin::getInstance()->getClaims()->claim($p,$p->getFaction()->getIdentifier());
                            var_dump(Plugin::getInstance()->getClaims()->getClaims());
                        } else {
                            $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous n'avez pas la rôle requis pour pouvoir protéger des zones"));
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->addLabel(Utils::createMessage("{PRIMARY}- {SECONDARY}Protégez une zone de {PRIMARY}16x16"));
            $form->addInput("Texte à afficher à l'entrée dans le claim", $player->getFaction()->getName(), $player->getFaction()->getName());
            $player->sendForm($form);
        }
    }
}