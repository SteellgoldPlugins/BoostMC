<?php

namespace Steellg0ld\Museum\forms;

use FormAPI\CustomForm;
use FormAPI\SimpleForm;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\forms\faction\MemberForm;
use Steellg0ld\Museum\utils\Utils;

class FactionForm{
    public static function createForm(Player $player)
    {
        {
            $form = new CustomForm(
                function (Player $p, $data) {
                    if ($data !== null) {
                        self::manage($p);
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_CREATE_TITLE_FORM"));
            $form->addLabel(Utils::getMessage($player, "FACTION_CREATE_LABEL_FORM"));
            $form->addInput(Utils::getMessage($player, "FACTION_CREATE_INPUT_FORM"));
            $form->addInput(Utils::getMessage($player, "FACTION_CREATE_INPUT_DESCRIPTION_FORM"));
            $player->sendForm($form);
        }
    }

    public static function manage(Player $player)
    {
        {
            $form = new SimpleForm(
                function (Player $p, $data) {
                    if ($data !== null) {
                        switch($data){
                            case 2:
                            MemberForm::list($p);
                            break;
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_MANAGE_TITLE_FORM"));
            $form->setContent(Utils::getMessage($player, "FACTION_MANAGE_LABEL_FORM"));
            $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_LEAVE_BUTTON_FORM"),0,"textures/ui/import");
            $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_FACTION_BUTTON_FORM"),0,"textures/ui/icon_unlocked");
            $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_BUTTON_FORM"),0,"textures/ui/icon_unlocked");
            $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_LOGS_BUTTON_FORM"),0,"textures/ui/icon_lock");
            $player->sendForm($form);
        }
    }
}