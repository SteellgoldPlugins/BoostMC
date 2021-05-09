<?php

namespace Steellg0ld\Museum\forms\factions;

use pocketmine\Server;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\CustomForm;
use Steellg0ld\Museum\forms\api\Form;
use Steellg0ld\Museum\utils\Utils;

class InviteForm
{
    public static function openInvitePlayerForm(MPlayer $player)
    {
        {
            $form = new CustomForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {
                        $s = Server::getInstance()->getPlayer($data[1]);
                        if ($s instanceof MPlayer) {
                            if (!$s->hasFaction()) {
                                $s->invitations_infos["expiration"] = time() + 60 * 1;
                                $s->invitations_infos["invitor"] = $p->getName();
                                $s->invitations_infos["faction"] = $p->getFaction()->getName();
                                $s->invitations_infos["role"] = $data[2];
                                $p->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous avez invité {PRIMARY}{NAME}{SECONDARY} dans votre faction, il à une minute pour accepter", ["{NAME}"], [$s->getName()]));
                                $s->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Le joueur {PRIMARY}{NAME}{SECONDARY}, vous a invité dans la faction {PRIMARY}{FACTION_NAME}, faite {PRIMARY}/f accept:deny {SECONDARY}pour accepter ou refusé la demande, vous avez {PRIMARY}1 minute {SECONDARY}top chrono !", ["{NAME}", "{FACTION_NAME}"], [$p->getName(), $p->getFaction()->getName()]));
                            } else {
                                $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Le joueur {ERROR}{NAME} {SECONDARY}à déjà une faction ({ERROR}{FACTION}{SECONDARY})", ["{NAME}", "{FACTION}"], [$s->getName(), $s->getFaction()->getName()]));
                            }
                        } else {
                            $p->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Le joueur {ERROR}{NAME}{SECONDARY} n'existe pas ou n'est pas connecté", ["{NAME}"], [$data[1]]));
                        }
                    }
                }
            );

            $form->setTitle(Form::FACTION_TITLE);
            $form->addLabel(Utils::createMessage("{PRIMARY}- {SECONDARY}Inviter un joueur dans votre faction"));
            $form->addInput("Nom du joueur");
            $form->addDropdown("Choisissez le rôle du joueur", ["Recrue", "Membre", "Officier"]);
            $player->sendForm($form);
        }
    }
}