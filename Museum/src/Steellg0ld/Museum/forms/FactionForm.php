<?php

namespace Steellg0ld\Museum\forms;

use FormAPI\CustomForm;
use FormAPI\SimpleForm;
use Steellg0ld\Museum\base\Faction;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\forms\faction\MemberForm;
use Steellg0ld\Museum\utils\Unicode;
use Steellg0ld\Museum\utils\Utils;

class FactionForm{
    public static function createForm(Player $player)
    {
        {
            $form = new CustomForm(
                function (Player $p, $data) {
                    if ($data !== null) {
                        if(isset($data[1]) && isset($data[2])){
                            Faction::create($p,$data[1],$data[2], $data[3]);
                        }else{
                            Utils::sendMessage($p,"MISSING_ARGUMENTS");
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_CREATE_TITLE_FORM"));
            $form->addLabel(Utils::getMessage($player, "FACTION_CREATE_LABEL_FORM"));
            $form->addInput(Utils::getMessage($player, "FACTION_CREATE_INPUT_NAME_FORM"),Utils::getMessage($player, "FACTION_CREATE_INPUT_NAME_PLACEHOLDER_FORM"));
            $form->addInput(Utils::getMessage($player, "FACTION_CREATE_INPUT_DESCRIPTION_FORM"),Utils::getMessage($player, "FACTION_CREATE_INPUT_DESCRIPTION_PLACEHOLDER_FORM"));
            $form->addInput(Utils::getMessage($player, "FACTION_CREATE_INPUT_CLAIMS_MESSAGE_FORM"),Utils::getMessage($player, "FACTION_CREATE_INPUT_CLAIMS_MESSAGE_PLACEHOLDER_FORM"));
            $form->addDropdown(Utils::getMessage($player, "FACTION_CREATE_DROPDOWN_INVITATIONS_FORM"),["Ouvert à tous","Sous invitations","Sous cautions"]);
            $player->sendForm($form);
        }
    }

    public static function openInfo(Player $player)
    {
        {
            $form = new CustomForm(
                function (Player $p, $data) {
                    if ($data !== null) {

                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_INFO_TITLE_FORM",["{FACTION}"],[$player->getFaction()]));
            $form->addLabel(Utils::getMessage($player, "FACTION_INFO_LABEL_FORM",["{POWER_UNI}","{POWER}","{POWER_MAX}","{MEMBERS}"],[
                Unicode::POWER,
                Faction::getPower($player->getFaction()),
                Faction::getPower($player->getFaction(),true),
                Faction::getMembers($player->getFaction(),true)
            ]));
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
                            case 0:
                                break;
                            case 2:
                            MemberForm::list($p);
                            break;
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_MANAGE_TITLE_FORM"));
            $form->setContent(Utils::getMessage($player,"FACTION_MANAGE_LABEL_FORM",["{FACTION}",$player->getFaction()]));
            $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_LEAVE_BUTTON_FORM"));
            $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_FACTION_BUTTON_FORM"));
            $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_BUTTON_FORM"));
            $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_LOGS_BUTTON_FORM"));
            $player->sendForm($form);
        }
    }

    public static function openFaction(Player $player) {
        {
            $form = new CustomForm(
                function (Player $p, $data) {
                    if ($data !== null) {
                        if(isset($data[1]) && isset($data[2]) && isset($data[3])){
                            if(Utils::exact($data[1],40)){
                                Faction::$factions[$p->getFaction()]["description"] = $data[1];
                                Faction::$factions[$p->getFaction()]["claim_message"] = $data[2];
                            }else Utils::sendMessage($p, "MESSAGE_TOO_BIG", ["{TYPE}", "{AUTHORIZED}"], [Utils::getMessage($p,"FACTION_DESCRIPTION"),Faction::MAX_CHARS_DESCRIPTION]);
                        }else{
                            Utils::sendMessage($p,"MISSING_ARGUMENTS");
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_EDIT_TITLE_FORM"));
            $form->addLabel(Utils::getMessage($player, "FACTION_EDIT_LABEL_FORM"));
            $form->addInput(Utils::getMessage($player, "FACTION_EDIT_INPUT_DESCRIPTION_FORM"),Faction::$factions[$player->getFaction()]["description"],Faction::$factions[$player->getFaction()]["description"]);
            $form->addInput(Utils::getMessage($player, "FACTION_EDIT_INPUT_CLAIMS_MESSAGE_FORM"),Faction::$factions[$player->getFaction()]["claim_message"],Faction::$factions[$player->getFaction()]["claim_message"]);
            $form->addDropdown(Utils::getMessage($player, "FACTION_EDIT_DROPDOWN_INVITATIONS_FORM"),["Ouvert à tous","Sous invitations","Sous cautions"]);
            $player->sendForm($form);
        }
    }
}