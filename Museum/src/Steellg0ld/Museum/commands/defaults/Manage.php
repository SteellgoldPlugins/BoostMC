<?php

namespace Steellg0ld\Museum\commands\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\forms\PlayerForm;

class Manage extends Command {
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            if($sender->isOp()){
                if(isset($args[0])){
                    $player = Server::getInstance()->getPlayer($args[0]);
                    if($player instanceof Player) PlayerForm::member($sender, $player);
                }
            }
        }
    }
}