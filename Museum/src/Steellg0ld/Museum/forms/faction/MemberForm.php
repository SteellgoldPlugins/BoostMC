<?php

namespace Steellg0ld\Museum\forms\faction;

use FormAPI\CustomForm;
use FormAPI\SimpleForm;
use pocketmine\item\Item;
use pocketmine\Server;
use Steellg0ld\Museum\base\Faction;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\base\Ranks;
use Steellg0ld\Museum\utils\Utils;

class MemberForm{
    public static function list(Player $player)
    {
        {
            $array = [];
            foreach (Faction::getMembers($player->getFaction()) as $member) {
                array_push($array, $member);
            }

            $form = new SimpleForm(
                function (Player $p, $data) use ($array) {
                    if ($data !== null) {
                        if($data == 0){
                            self::invite($p);
                        }else{
                            self::member($p, $array[$data - 1]);
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_BUTTON_FORM"));
            $form->setContent(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_LABEL_FORM"));
            $form->addButton(Utils::getMessage($player, "FACTION_MANAGE_INVITE_BUTTON_FORM"));
            foreach ($array as $member) {
                $form->addButton($member);
            }
            $player->sendForm($form);
        }
    }

    public static function invite(Player $player)
    {
        {
            $form = new CustomForm(
                function (Player $p, $data) {
                    if ($data !== null) {
                        $player = Server::getInstance()->getPlayer($data[1]);
                        if($player instanceof Player){
                            if(!$player->hasFaction()){
                                Faction::sendInvitation($player,$p->getFaction());
                                Utils::sendMessage($p,"FACTION_INVITE", ["{INVITED}"], [$player->getName()]);
                                Utils::sendMessage($player,"FACTION_INVITED",[
                                    "{OFFICIER}",
                                    "{FACTION}"
                                ],[
                                    $p->getName(),
                                    $p->getFaction()
                                ]);
                            }else{
                                Utils::sendMessage($p,"FACTION_PLAYER_ALREADY_FACTION", ["{PLAYER}", "{FACTION}"], [$player->getName(), $player->getFaction()]);
                            }
                        }else{
                            Utils::sendMessage($p, "FACTION_INVITE_PLAYER_DISCONNECTED");
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_BUTTON_FORM"));
            $form->addLabel(Utils::getMessage($player, "FACTION_INVITE_LABEL_FORM"));
            $form->addInput(Utils::getMessage($player,"FACTION_INVITE_INPUT_FORM"),0);
            $form->addDropdown(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_LABEL_FORM"),["Recrue", "Membre", "Officier"],0);
            $player->sendForm($form);
        }
    }

    public static function member(Player $player, String $name)
    {
        {
            $form = new SimpleForm(
                function (Player $p, $data) use ($name) {
                    if ($data !== null) {

                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "FACTION_MANAGE_MEMBERS_BUTTON_FORM"));
            $form->setContent(Utils::getMessage($player, "FACTION_MANAGE_MEMBER_LABEL_FORM", ["{NAME}", "{ROLE}", "{JOIN_DATE}", "{MONEY_INVEST}", "{LAST_CONNECT}", "{KILLS}", "{DEATHS}"],[$name,"","25 décembre 2020",5620,"12 mai 2021", 6, 1]));
            $player->sendForm($form);
        }
    }
}