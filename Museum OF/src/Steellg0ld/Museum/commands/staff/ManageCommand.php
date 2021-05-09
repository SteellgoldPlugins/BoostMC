<?php

namespace Steellg0ld\Museum\commands\staff;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class ManageCommand extends Command
{
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $player, string $commandLabel, array $args)
    {
    }
}