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

            $form->setTitle(Form::FACTION_TITLE);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}Améliorations de {PRIMARY}" . $player->getFaction()->getName()) . "\n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Banque de faction: {PRIMARY}" . $player->getFaction()->getMoney()) . "$\n");
            $form->addButton("Amélioration de joueurs\n   Niveau 2   ");
            $form->addButton("Amélioration de quantité\n   Niveau 1   ");
            $form->addButton("Amélioration de vie\n   Niveau 0   ");

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
                function (MPlayer $p, $data) use ($id, $level) {
                    if ($data !== null) {
                        switch ($id) {
                            case MFaction::UPGRADES[1]:
                                $p->getFaction()->slotChestUpdate($level++);
                                // TODO REMOVE MONEY
                                break;
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
            $form->addButton("Améliorer");

            $player->sendForm($form);
        }
    }
}