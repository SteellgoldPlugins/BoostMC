<?php

namespace Steellg0ld\Museum\forms;

use pocketmine\Server;
use raklib\server\ServerInstance;
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
                                    $faction_id = uniqid();
                                    $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous venez de créer la faction nommé {PRIMARY}".$data[1]));

                                    Plugin::getInstance()->getEconomyAPI()->delMoney($p, 500);
                                    $p->faction_id = $faction_id;
                                    Plugin::getInstance()->getDatabase()->factionRegister($faction_id,$data[1],$p->getName());
                                    if($data[2]) Server::getInstance()->broadcastMessage(Utils::createMessage("{DANGER}- {SECONDARY}Une faction nommé {DANGER}" . $data[1] . "{SECONDARY}, viens d'être créer par {DANGER}" . $p->getName()));
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
                        switch ($data){
                            case 1:
                                self::members($p);
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}Votre faction: {PRIMARY}".$player->getFaction()->getName()."{SECONDARY}")."\n".
                Utils::createMessage("{PRIMARY}> {SECONDARY}Claim(s) actif(s): {SECONDARY}".$player->getFaction()->getFactionClaims(true))."\n".
                Utils::createMessage("{PRIMARY}> {SECONDARY}Claim(s) inactif(s): {SECONDARY}".$player->getFaction()->getFactionClaims(false)));
            $form->addButton("§cQuitter la faction\n§c§l" . $player->getFaction()->getName());
            $form->addButton("Gérer les membres\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer la faction\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer les claims\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer le coffre\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Améliorations de faction\n" . $player->getFaction()->getFactionAccess($player));
            $player->sendForm($form);
        }
    }

    public static function invite(MPlayer $player){
        {
            $form = new CustomForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        $s = Server::getInstance()->getPlayer($data[1]);
                        if($s instanceof MPlayer) {
                            $s->hasFactionInvite = true;
                            $s->invitations_infos["expiration"] = time() + 60 * 1;
                            $s->invitations_infos["invitor"] = $p->getName();
                            $s->invitations_infos["faction"] = $p->getFaction()->getName();
                            $s->invitations_infos["role"] = $data[2];
                            $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous avez invité {PRIMARY}{NAME} {SECONDARY}dans votre faction, il à une minute pour accepter", ["{NAME}"], [$s->getName()]));
                            $s->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Le joueur {PRIMARY}{NAME}{SECONDARY}, vous a invité dans la faction {PRIMARY}{FACTION_NAME}, faite {PRIMARY}/f accept:deny {SECONDARY}pour accepter ou refusé la demande, vous avez {PRIMARY}1 minute {SECONDARY}top chrono !", ["{NAME}", "{FACTION_NAME}"], [$p->getName(), $p->getFaction()->getName()]));
                        }else{
                            $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Le joueur {ERROR}{NAME}{SECONDARY} n'existe pas ou n'est pas connecté"));
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->addLabel("A faire");
            $form->addInput("Nom du joueur");
            $form->addDropdown("Grade", ["Recrue", "Membre", "Officier"]);
            $player->sendForm($form);
        }
    }

    public static function members(MPlayer $player){
        {
            $form = new SimpleForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        switch ($data){
                            case 0:
                                self::invite($p);
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}Les membres de la faction: {PRIMARY}".$player->getFaction()->getName()."{SECONDARY}")."\n".
                Utils::createMessage("{PRIMARY}> {SECONDARY}Membre(s) actif(s): {SECONDARY}".$player->getFaction()->getMembersCount(true))."\n".
                Utils::createMessage("{PRIMARY}> {SECONDARY}Membre(s) inactif(s): {SECONDARY}".$player->getFaction()->getMembersCount(false)));
            $form->addButton("Inviter un joueur");
            var_dump($player->getFaction()->getMembers());
            foreach (explode(" ", $player->getFaction()->getMembers()) as $member){
                $form->addButton($member . "\n" . MFaction::playerStatus($member));
            }
            $player->sendForm($form);
        }
    }
}
