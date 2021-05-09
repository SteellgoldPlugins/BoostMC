<?php

namespace Steellg0ld\Museum\commands\faction\subcommands;

use pocketmine\Server;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\utils\Utils;

class InviteSubCommand
{
    public function execute_accept(MPlayer $invited)
    {
        if (!$invited->hasFactionInvite) {
            $invited->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous n'avez aucune invitation actuellement"));
        }

        $invitor = Server::getInstance()->getPlayer($invited->invitations_infos["invitor"]);
        if ($invitor instanceof MPlayer) $faction = $invitor->getFaction();

        if ($invited->invitations_infos["expiration"] >= time()) {
            $invited->faction_id = $invitor->getFaction()->getIdentifier();
            $invited->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous avez accepté(e) la demande, bienvenue dans la faction {PRIMARY}{FACTION}{SECONDARY}, en tant que {PRIMARY}{ROLE}", ["{FACTION}", "{ROLE}"], [$invited->invitations_infos["faction"], MFaction::ROLES[$invited->invitations_infos["role"]]]));
            if ($invitor instanceof MPlayer) $invitor->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Le joueur {PRIMARY}{NAME}{SECONDARY} a accepté votre invitation, il est désormais dans votre faction en tant que {PRIMARY}{ROLE}", ["{NAME}", "{ROLE}", "{FACTION}"], [$invited->getName(), MFaction::ROLES[$invited->invitations_infos["role"]]]));

            $faction->addMember($invited->getName());
        } else {
            $invited->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}L'invitation de {NAME} à expirée, veuillez lui demander de vous réinviter", ["{NAME}"], [$invited->invitations_infos["invitor"]]));
            if ($invitor instanceof MPlayer) $invitor->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}L'invitation à expirée"));
        }

        $invited->invitations_infos = [];
        $invited->hasFactionInvite = false;
    }

    public function execute_deny(MPlayer $invited)
    {
        if (!$invited->hasFactionInvite) {
            $invited->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}Vous n'avez aucune invitation actuellement"));
        }

        $invitor = Server::getInstance()->getPlayer($invited->invitations_infos["invitor"]);
        if ($invited->invitations_infos["expiration"] >= time()) {
            $invited->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous avez refusé l'invitation de {PRIMARY}{NAME}"));
            if ($invitor instanceof MPlayer) $invitor->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Le joueur {PRIMARY}{NAME} a refusé(e) votre invitation"));
        } else {
            $invited->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}L'invitation de {PRIMARY}{NAME} {SECONDARY}à expirée, veuillez lui demander de vous réinviter"));
            if ($invitor instanceof MPlayer) $invitor->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}L'invitation à expirée"));
        }

        $invited->invitations_infos = [];
        $invited->hasFactionInvite = false;
    }
}