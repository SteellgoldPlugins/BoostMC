<?php

namespace Steellg0ld\Museum\commands\faction;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\commands\faction\subcommands\InviteSubCommand;
use Steellg0ld\Museum\forms\FactionForm;
use Steellg0ld\Museum\forms\factions\ClaimsForm;
use Steellg0ld\Museum\utils\Utils;

class FactionCommand extends Command
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

        $invite = new InviteSubCommand();

        if (isset($args[0])) {
            switch ($args[0]) {
                case "a":
                case "accept":
                    $invite->execute_accept($sender);
                    break;
                case "d":
                case "deny":
                    $invite->execute_deny($sender);
                    break;
                case "c":
                case "claim":
                    ClaimsForm::openClaim($sender);
                    break;
            }
        } else {
            if (!$sender->hasFaction()) {
                FactionForm::createFaction($sender);
            } else {
                FactionForm::factionForm($sender);
            }
        }
    }
}