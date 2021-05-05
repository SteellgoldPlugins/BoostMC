<?php

namespace Steellg0ld\Museum\forms;

use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\CustomForm;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\forms\api\SimpleForm;
use Steellg0ld\Museum\utils\Utils;

class FactionForm {
    public static function createFaction(MPlayer $player){
        {
            $form = new CustomForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {

                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->addInput("Nom de la faction");
            $player->sendForm($form);
        }
    }

    public static function factionForm(MPlayer $player){
        {
            $form = new SimpleForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {

                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}Votre faction: {PRIMARY}".$player->getFaction()->getName()."{SECONDARY}")."\n".
                Utils::createMessage("{PRIMARY}> {SECONDARY}Claim(s) actif(s): {SECONDARY}".$player->getFaction()->getFactionClaims(true))."\n".
                Utils::createMessage("{PRIMARY}> {ERROR}Claim(s) inactif(s): {SECONDARY}".$player->getFaction()->getFactionClaims(false)));
            $form->addButton("§cQuitter la {FACTION}" . $player->getFaction()->getName());
            $form->addButton("Gérer les membres\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer la faction\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer les claims\n" . $player->getFaction()->getFactionAccess($player));
            $player->sendForm($form);
        }
    }
}