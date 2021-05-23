<?php

namespace Steellg0ld\Museum\forms\factions;

use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\forms\api\SimpleForm;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class UpgradesForm
{
    public static function openList(MPlayer $player)
    {
        {

            $form = new SimpleForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        self::openInfos($p, MFaction::UPGRADES[$data]);
                    }
                }
            );

            $faction = $player->getFaction();

            $form->setTitle(Form::FACTION_TITLE);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}Améliorations de {PRIMARY}" . $faction->getName()) . "\n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Banque de faction: {PRIMARY}" . $faction->getMoney()) . "$\n");
            $form->addButton("Amélioration de joueurs\nNiveau " . $faction->getUpgrade("player_slot"));
            $form->addButton("Amélioration de quantité\nNiveau " . $faction->getUpgrade("slot_faction_chest"));
            $form->addButton("Amélioration de vie\nNiveau " . $faction->getUpgrade("heal_home_faction"));

            $player->sendForm($form);
        }
    }

    public static function openInfos(MPlayer $player, String $id)
    {
        {
            $upgrades = Plugin::getInstance()->getConfigFile("upgrades");
            $faction = $player->getFaction();
            $level = $faction->getUpgrade($id);

            $form = new SimpleForm(
                function (MPlayer $p, $data) use ($id, $level, $upgrades, $faction) {
                    if ($data !== null) {
                        if($upgrades->get($id)["prices"][$level + 1]["money"] >= $faction->getMoney()){
                            if($p->getInventory()->contains(Item::get(1002,0,$upgrades->get($id)["prices"][$level + 1]["fragments"]))){
                                $p->getFaction()->slotChestUpdate($level + 1);
                                $faction->updateMoney($faction->getMoney() - (int)$upgrades->get($id)["prices"][$level + 1]["money"]);
                                $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}L'amélioration a bien été appliqué"));
                            }else{
                                $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous n'avez pas assez de {ERROR}fragements {SECONDARY}dans votre inventaire pour réussir l'amélioration"));
                            }
                        }else{
                            $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Il manque {ERROR}{MISSING}$ {SECONDARY}dans le banque de faction"));
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_UPGRADE_TITLE . $upgrades->get($id)["name"]);
            $form->setContent($level == 8 ? Utils::createMessage("{PRIMARY}- {SECONDARY}Vous êtes niveau maximum sur cette amélioration") : Utils::createMessage("{PRIMARY}- {SECONDARY}" . $upgrades->get($id)["description"] . "\n" .
                "\n" .
                "{PRIMARY}> {SECONDARY}Voici les coûts pour le {PRIMARY}niveau " . ($level + 1) .": \n" .
                "{PRIMARY}> {SECONDARY}Argent nécessaire: {PRIMARY}" . $upgrades->get($id)["prices"][$level + 1]["money"] . "$\n" .
                "{PRIMARY}> {SECONDARY}Fragment nécessaire: {PRIMARY}" . $upgrades->get($id)["prices"][$level + 1]["fragments"]));
            if($level !== 8) $form->addButton("Améliorer");

            $player->sendForm($form);
        }
    }
}