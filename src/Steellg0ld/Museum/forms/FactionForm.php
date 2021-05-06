<?php

namespace Steellg0ld\Museum\forms;

use pocketmine\Server;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\CustomForm;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\forms\api\SimpleForm;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class FactionForm {
    public static function createFaction(MPlayer $player){
        {
            $form = new CustomForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        if($p->getMoney() >= Utils::FACTION_CREATE_PRICE){
                            if(isset($data[1])){
                                if(!MFaction::factionExist($data[1])){
                                    $faction_id = uniqid("FACTION-");
                                    $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous venez de créer la faction nommé {PRIMARY}".$data[1]));

                                    $factions = Plugin::getInstance()->getFactions();
                                    $factions->set($faction_id, $p->getName());
                                    $factions->save();

                                    if($data[2]) Server::getInstance()->broadcastMessage(Utils::createMessage("{DANGER}- {SECONDARY}Une faction nommé {DANGER}" . $data[1] . " {SECONDARY}, viens d'être créer par {DANGER}" . $p->getName()));
                                }else{
                                    $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}La faction {ERROR}" . $data[1] . " {SECONDARY}existe déjà !"));
                                }
                            }else{
                                $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous n'avez pas préciser le nom de {ERROR}votre faction"));
                            }
                        }else{
                            $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous n'avez pas assez d'argent pour pouvoir {ERROR}créer une faction"));
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->addLabel(Utils::createMessage("{PRIMARY}- {SECONDARY}Pour créer une faction cela vous coûtera {PRIMARY}500{ECONOMY_SYMBOL}"));
            $form->addInput("Nom de la faction","LesProduitsLaitiers");
            $form->addToggle("Annoncer la création",false);
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
                Utils::createMessage("{PRIMARY}> {SECONDARY}Claim(s) inactif(s): {SECONDARY}".$player->getFaction()->getFactionClaims(false)));
            $form->addButton("§cQuitter la {FACTION}" . $player->getFaction()->getName());
            $form->addButton("Gérer les membres\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer la faction\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer les claims\n" . $player->getFaction()->getFactionAccess($player));
            $player->sendForm($form);
        }
    }
}