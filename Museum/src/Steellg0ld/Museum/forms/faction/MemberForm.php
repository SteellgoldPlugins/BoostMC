<?php

namespace Steellg0ld\Museum\forms\faction;

use FormAPI\CustomForm;
use FormAPI\SimpleForm;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\base\Ranks;
use Steellg0ld\Museum\utils\Utils;

class MemberForm{
    public static function list(Player $player)
    {
        {
            $form = new SimpleForm(
                function (Player $p, $data) {
                    if ($data !== null) {
                        self::member($p);
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_BUTTON_FORM"));
            $form->setContent(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_LABEL_FORM"));
            $form->addButton("Joueur 1\nMembre",0,"textures/ui/online");
            $form->addButton("Joueur 2\nMembre",0,"textures/ui/offline");
            $form->addButton("Joueur 3\nOfficier",0,"textures/ui/offline");
            $form->addButton("Joueur 4\nChef",0,"textures/ui/offline");
            // $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_INVITE_BUTTON_FORM"),0,"textures/ui/invite_base");
            // $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_MEMBER_BUTTON_FORM"),0,"textures/ui/icon_setting");
            $player->sendForm($form);
        }
    }

    public static function member(Player $player)
    {
        {
            $form = new SimpleForm(
                function (Player $p, $data) {
                    if ($data !== null) {

                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_BUTTON_FORM"));
            $form->setContent(Utils::getMessage($player, "FACTION_MANAGE_MEMBER_LABEL_FORM", ["{NAME}", "{ROLE}", "{JOIN_DATE}", "{MONEY_INVEST}", "{LAST_CONNECT}", "{KILLS}", "{DEATHS}"],["Joueur","Chef","25 décembre 2020", 5620,"12 mai 2021", 6, 1]));
            $player->sendForm($form);
        }
    }
}