<?php

namespace Steellg0ld\Museum\commands\faction;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Steellg0ld\Museum\base\MPlayer;

class FactionCommand extends Command{
    public function __construct(string $name, string $description, array $aliases){
        parent::__construct($name, $description, "", $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$sender instanceof MPlayer){
            $sender->sendMessage();
        }
    }
}