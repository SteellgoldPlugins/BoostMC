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
            $form = new SimpleForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        switch ($data) {
                            case 0:
                                break;
                        }
                    }
                }
            );

            $upgrades = Plugin::getInstance()->getConfigFile("upgrades");
            $faction = $player->getFaction();
            $level = $faction->getUpgrade($id);

            $form->setTitle(Form::FACTION_UPGRADE_TITLE . $upgrades->get($id)["name"]);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}" . $upgrades->get($id)["description"] . "\n" .
                "\n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Voici les coûts pour l'amélioration supérieur ({PRIMARY}Niveau " . ($level + 1) ."{SECONDARY}): \n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Argent nécessaire: {PRIMARY}" . $upgrades->get($id)["prices"][$level + 1]["money"]) . "$\n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Fragment nécessaire: {PRIMARY}" . $upgrades->get($id)["prices"][$level + 1]["fragments"]))));
            $form->addButton("Créer l'amélioration");

            $player->sendForm($form);
        }
    }
}