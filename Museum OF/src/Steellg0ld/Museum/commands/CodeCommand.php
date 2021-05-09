<?php

namespace Steellg0ld\Museum\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\CodeForm;
use Steellg0ld\Museum\utils\Utils;

class CodeCommand extends Command
{
    public function __construct(string $name, string $description, array $aliases)
    {
        parent::__construct($name, $description, "", $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof MPlayer) {
            $sender->sendMessage(Utils::createMessage(Utils::CONSOLE_ERROR));
            return;
        }

        CodeForm::open($sender);
    }
}