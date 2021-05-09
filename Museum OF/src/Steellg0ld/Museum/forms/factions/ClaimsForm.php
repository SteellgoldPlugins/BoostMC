<?php

namespace Steellg0ld\Museum\forms\factions;

use Steellg0ld\Museum\base\MFaction;
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
                            $toPos = $p->getLevel()->getChunkAtPosition($p->asVector3());
                            $chunkXP = $toPos->getX();
                            $chunkZP = $toPos->getZ();

                            if($data[1] !== $p->getFaction()->getName()) {
                                $msg = base64_encode($data[1]);
                            }else{
                                $msg = null;
                            }

                            if(!Plugin::getInstance()->getClaims()->isInClaim($p->getLevel(), $chunkXP, $chunkZP)){
                                Plugin::getInstance()->getClaims()->claim($p,$p->getFaction()->getIdentifier(),$msg);
                                $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous venez de protéger le chunk {PRIMARY}$chunkXP{SECONDARY}:{PRIMARY}$chunkZP"));
                            }else{
                                $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}La zone est déjà protéger par la faction {ERROR}".MFaction::getNameByIdentifier(Plugin::getInstance()->getClaims()->getFactionClaim($p->getLevel(), $chunkXP, $chunkZP))));
                            }
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