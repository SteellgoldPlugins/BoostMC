<?php

use pocketmine\event\player\PlayerCommandPreprocessEvent;

class HelpCommand implements \pocketmine\event\Listener{
    /**
     * @param PlayerCommandPreprocessEvent $event
     */
    public function PlayerCommandPreprocess(PlayerCommandPreprocessEvent $event) : void
    {
        $help = "Commance désactivé.";

        $player = $event->getPlayer();

        if(strpos($event->getMessage(), "/help")){
            $player->sendMessage($help);
            $event->setCancelled();
        }
    }
}