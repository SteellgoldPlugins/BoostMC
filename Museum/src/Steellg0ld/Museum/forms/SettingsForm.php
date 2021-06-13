<?php

namespace Steellg0ld\Museum\forms;

use FormAPI\CustomForm;
use Steellg0ld\Museum\base\Economy;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\base\Ranks;
use Steellg0ld\Museum\utils\Unicode;
use Steellg0ld\Museum\utils\Utils;

class SettingsForm{
    public static function openForm(Player $player)
    {
        {
            $form = new CustomForm(
                function (Player $p, $data) {
                    if ($data !== null) {
                        $p->settings["economy_symbol"] = $data[1] + 1;
                        $p->lang = Utils::LANGS[$data[2]];
                        $p->settings["armor_pvp_status"] = $data[3] ?? false;
                        $p->settings["coords"] = $data[4] ?? false;
                        $p->settings["unicode"] = $data[5] ?? 0;
                        Utils::sendMessage($p, "SETTINGS_UPDATED");
                    }
                }
            );

            $form->setTitle(Unicode::SETTINGS . " " . Utils::getMessage($player, "SETTINGS_TITLE_FORM"));
            $form->addLabel(Utils::getMessage($player, "SETTINGS_LABEL_FORM", ["{RANK}"], [Ranks::translate($player,$player->getRank())]));
            $form->addDropdown(Utils::getMessage($player, "SETTINGS_DROPDOWN_0"),[Economy::DOL,Economy::EUR,Economy::ARO,Economy::YEN]);
            $form->addDropdown(Utils::getMessage($player, "SETTINGS_DROPDOWN_1"),[Utils::getMessage($player, "SETTINGS_DROPDOWN_1_0"),Utils::getMessage($player, "SETTINGS_DROPDOWN_1_1"),Utils::getMessage($player, "SETTINGS_DROPDOWN_1_2"),Utils::getMessage($player, "SETTINGS_DROPDOWN_1_3"),Utils::getMessage($player, "SETTINGS_DROPDOWN_1_4")],array_search($player->lang,Utils::LANGS));
            $form->addToggle(Utils::getMessage($player, "SETTINGS_TOGGLE_1"),$player->settings["armor_pvp_status"]);
            $form->addToggle(Utils::getMessage($player, "SETTINGS_TOGGLE_2"), $player->settings["coords"]);
            if($player->hasRank(Ranks::HELPER,Ranks::MODERATOR,Ranks::ADMIN)){
                $form->addDropdown(Utils::getMessage($player, "SETTINGS_DROPDOWN_2"),[Utils::getMessage($player, "SETTINGS_DROPDOWN_2_0", ["[U]"], [Unicode::getMFace(0, $player->getRank())]), Utils::getMessage($player, "SETTINGS_DROPDOWN_2_1", ["[U]"], [Unicode::getMFace(1, $player->getRank())]),Utils::getMessage($player, "SETTINGS_DROPDOWN_2_2", ["[U]"], [Unicode::getMFace(2, $player->getRank())]),Utils::getMessage($player, "SETTINGS_DROPDOWN_2_3", ["[U]"], [Unicode::getMFace(3, $player->getRank())])],$player->settings['unicode']);
            }
            $player->sendForm($form);
        }
    }
}