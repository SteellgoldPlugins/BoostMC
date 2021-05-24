<?php

namespace Steellg0ld\Museum\forms;

use FormAPI\CustomForm;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\base\Ranks;
use Steellg0ld\Museum\utils\Utils;

class SettingsForm{
    public static function openForm(Player $player)
    {
        {
            $form = new CustomForm(
                function (Player $p, $data) {
                    if ($data !== null) {
                        switch ($data[2]){
                            case 0:
                                $p->lang = "fr_FR";
                                break;
                            case 1:
                                $p->lang = "en_EN";
                                break;
                            case 2:
                                $p->lang = "es_ES";
                                break;
                            case 3:
                                $p->lang = "it_IT";
                                break;
                            case 4:
                                $p->lang = "ch_CH";
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "SETTINGS_TITLE_FORM"));
            $form->addLabel(Utils::getMessage($player, "SETTINGS_LABEL_FORM", ["{RANK}"], [Ranks::translate($player,$player->getRank())]));
            $form->addDropdown(Utils::getMessage($player, "SETTINGS_DROPDOWN_0"),["$","€","@"]);
            $form->addDropdown(Utils::getMessage($player, "SETTINGS_DROPDOWN_1"),[Utils::getMessage($player, "SETTINGS_DROPDOWN_1_0"),Utils::getMessage($player, "SETTINGS_DROPDOWN_1_1"),Utils::getMessage($player, "SETTINGS_DROPDOWN_1_2"),Utils::getMessage($player, "SETTINGS_DROPDOWN_1_3"),Utils::getMessage($player, "SETTINGS_DROPDOWN_1_4")]);
            $player->sendForm($form);
        }
    }
}