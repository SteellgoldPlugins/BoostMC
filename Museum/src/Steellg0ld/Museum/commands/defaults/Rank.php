<?php

namespace Steellg0ld\Museum\commands\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\forms\FactionForm;
use Steellg0ld\Museum\forms\SettingsForm;

class Rank extends Command {
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            $sender->rank = (int)$args[0];
        }
    }
}