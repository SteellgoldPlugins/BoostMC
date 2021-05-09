<?php

namespace Steellg0ld\Museum\commands\faction\subcommands;

use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class ClaimSubCommand {
    public function execute(MPlayer $player){
        if ($player->faction_role >= 2) {
            $toPos = $player->getLevel()->getChunkAtPosition($player->asVector3());
            $chunkXP = $toPos->getX();
            $chunkZP = $toPos->getZ();

            if(!Plugin::getInstance()->getClaims()->isInClaim($player->getLevel(), $chunkXP, $chunkZP)){
                Plugin::getInstance()->getClaims()->claim($player,$player->getFaction()->getIdentifier());
                $player->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous venez de protéger le chunk {PRIMARY}$chunkXP{SECONDARY}:{PRIMARY}$chunkZP"));
            }else{
                $player->sendMessage(Utils::createMessage("{ERROR}- {SECONDARY}La zone est déjà protéger par la faction {ERROR}".MFaction::getNameByIdentifier(Plugin::getInstance()->getClaims()->getFactionClaim($player->getLevel(), $chunkXP, $chunkZP))));
            }
        } else {
            $player->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Vous n'avez pas la rôle requis pour pouvoir protéger des zones"));
        }
    }
}