<?php

namespace Steellg0ld\Museum\forms;

use pocketmine\Server;
use Steellg0ld\Museum\api\Claims;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\CustomForm;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\forms\api\SimpleForm;
use Steellg0ld\Museum\forms\factions\ManageForm;
use Steellg0ld\Museum\json\JSONProvider;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class FactionForm
{
    public static function createFaction(MPlayer $player, JSONProvider $provider)
    {
        {
            $form = new CustomForm(
                function (MPlayer $p, $data) use ($provider) {
                    if ($data !== null) {
                        if ($p->getMoney() >= Utils::FACTION_CREATE_PRICE) {
                            if (empty($data[1]) AND empty($data[2])) {
                                if (!MFaction::factionExist($data[1])) {
                                    $faction_id = uniqid();

                                    Plugin::getInstance()->getEconomyAPI()->delMoney($p, 500);
                                    $p->faction_id = $faction_id;
                                    $p->faction_role = 3;
                                    Claims::$claims[$faction_id] = array();

                                    $provider->getDataProvider()->createFaction($faction_id,$p,$data[1], $data[2],[$p->getName()],20);
                                    $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous venez de créer la faction nommé {PRIMARY}" . $data[1]));
                                    if ($data[3]) Server::getInstance()->broadcastMessage(Utils::createMessage("{DANGER}- {SECONDARY}Une faction nommé {DANGER}" . $data[1] . "{SECONDARY}, viens d'être créer par {DANGER}" . $p->getName()));
                                } else {
                                    $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}La faction {ERROR}" . $data[1] . " {SECONDARY}existe déjà !"));
                                }
                            } else {
                                $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous n'avez pas préciser le nom de {ERROR}votre faction {SECONDARY}ou {ERROR}la description"));
                            }
                        } else {
                            $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous n'avez pas assez d'argent pour pouvoir {ERROR}créer une faction"));
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->addLabel(Utils::createMessage("{PRIMARY}- {SECONDARY}Pour créer une faction cela vous coûtera {PRIMARY}500{ECONOMY_SYMBOL}"));
            $form->addInput("Nom de la faction", "LesProduitsLaitiers");
            $form->addInput("Description", "Ma superbe faction trop cool");
            $form->addToggle("Annoncer la création", false);
            $player->sendForm($form);
        }
    }

    public static function factionForm(MPlayer $player)
    {
        {
            $form = new SimpleForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        switch ($data) {
                            case 1:
                                ManageForm::openMembersListForm($p);
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}Votre faction: {PRIMARY}" . $player->getFaction()->getName() . "{SECONDARY}") . "\n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Claim(s) actif(s): {SECONDARY}" . $player->getFaction()->getFactionClaims(true)) . "\n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Claim(s) inactif(s): {SECONDARY}" . $player->getFaction()->getFactionClaims(false)));
            $form->addButton("§cQuitter la faction\n§c§l" . $player->getFaction()->getName());
            $form->addButton("Gérer les membres\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer la faction\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer les claims\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Gérer le coffre\n" . $player->getFaction()->getFactionAccess($player));
            $form->addButton("Améliorations de faction\n" . $player->getFaction()->getFactionAccess($player));
            $player->sendForm($form);
        }
    }
}
