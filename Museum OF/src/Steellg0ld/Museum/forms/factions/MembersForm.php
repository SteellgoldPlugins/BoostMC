<?php

namespace Steellg0ld\Museum\forms\factions;

use pocketmine\Server;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\forms\api\SimpleForm;
use Steellg0ld\Museum\utils\Utils;

class MembersForm
{
    public static function openMemberInfoForm(MPlayer $player, string $member)
    {
        {
            $form = new SimpleForm(
                function (MPlayer $p, $data) use ($member) {
                    if ($data !== null) {
                        switch ($data) {
                            case 0:
                                $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous venez d'expulser {ERROR}{NAME} {SECONDARY}de la faction", ["{NAME}"], [$member]));
                                $s = Server::getInstance()->getPlayer($member);
                                $p->getFaction()->removeMember($member);
                                if ($s instanceof MPlayer) $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous venez d'être expulser de la faction {ERROR}{FACTION} {SECONDARY}par {ERROR}{NAME}", ["{FACTION}", "{NAME}"], [$p->getFaction()->getName(), $p->getName()]));
                                break;
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->setContent(Utils::createMessage("{PRIMARY}- {SECONDARY}Membre: {PRIMARY}" . $member . "\n" .
                Utils::createMessage("{PRIMARY}> {SECONDARY}Actuellement: " . MFaction::playerStatus($member) . "\n" .
                    Utils::createMessage("§r{PRIMARY}> {SECONDARY}Invité le: {SECONDARY}" . $player->getFaction()->getInvitedDate($member)) . "\n" .
                    Utils::createMessage("{PRIMARY}> {SECONDARY}Role: {SECONDARY}" . MFaction::ROLES[$player->getFaction()->getOfflinePlayerFactionRole($member)]))));
            if ($player->getName() !== $member) {
                if ($player->faction_role >= 2) {
                    $form->addButton("§cExpulser");
                    if ($player->faction_role == 3) {
                        $form->addButton("§cTransferer la propriété ");
                    }
                }
            }
            $player->sendForm($form);
        }
    }
}