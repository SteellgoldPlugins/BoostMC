<?php

namespace Steellg0ld\Museum\forms\factions;

use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\forms\api\SimpleForm;
use Steellg0ld\Museum\utils\Utils;

class ManageForm
{
    public static function openMembersListForm(MPlayer $player)
    {
        {
            $members = array();
            foreach ($player->getFaction()->getMembers() as $name => $role) {
                array_push($members, ["name" => $name, "status" => MFaction::playerStatus($name), "role" => $role]);
            }

            $form = new SimpleForm(
                function (MPlayer $p, $data) use ($members) {
                    if ($data !== null) {
                        switch ($data) {
                            case 0:
                                InviteForm::openInvitePlayerForm($p);
                                break;
                            default:
                                MembersForm::openMemberInfoForm($p, $members[$data - 1]["name"], $members[$data - 1]["role"]);
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}Les membres de la faction: {PRIMARY}" . $player->getFaction()->getName() . "{SECONDARY}") . "\n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Membre(s) actif(s): {SECONDARY}" . $player->getFaction()->getMembersCount(true)) . "\n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Membre(s) inactif(s): {SECONDARY}" . $player->getFaction()->getMembersCount(false)));
            $form->addButton("Inviter un joueur");
            foreach ($members as $member) {
                if ($member["name"] !== "") {
                    $form->addButton($member["name"] . "\n" . $member["status"]);
                }
            }
            $player->sendForm($form);
        }
    }
}