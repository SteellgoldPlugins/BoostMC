<?php

namespace Steellg0ld\Museum\listeners;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\CodeForm;
use pocketmine\Server;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class PlayerListeners implements Listener {
    public function onCreation(PlayerCreationEvent $event){
        $event->setPlayerClass(MPlayer::class);
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $event->setJoinMessage("");
        if(!$player instanceof MPlayer) return;
        if(Utils::checkVPN($player->getAddress())) $player->close("", Utils::createMessage("{ERROR}Vous ne pouvez pas utiliser de VPN sur le serveur"));

        if(!$player->hasPlayedBefore()) {
            $player->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Si vous avez un code de parrainage, vous pouvez le définir pendant les 72 heures suivantes, une fois ce temp dépassé aucun code ne pourra être défini"));
            $player->sendMessage(Utils::createMessage("{PRIMARY}- {SECONDARY}Précision: Pendant ces 72h vous ne pouvez pas créer de codes vous même"));

            $player->register();
            CodeForm::enterCode($player);
        }

        Server::getInstance()->broadcastTip(Utils::createMessage("{PRIMARY}+ {SECONDARY}".$player->getName() . " {PRIMARY}+"));
        $player->hasFactionInvite = false;
        $player->dataConnect();
    }

    public function onQuit(PlayerQuitEvent $event){
        $event->setQuitMessage("");
        $player = $event->getPlayer();
        if(!$player instanceof MPlayer) return;
        Plugin::getInstance()->getDatabase()->updatePlayer($player->getName(),$player->rank, $player->money, $player->faction_id,$player->code,$player->hasJoinedWithCode, $player->enterCodeWaitEnd);
        Server::getInstance()->broadcastTip(Utils::createMessage("{ERROR}- {SECONDARY}".$player->getName() . " {ERROR}-"));
    }
}