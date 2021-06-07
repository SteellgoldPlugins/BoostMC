<?php

namespace Steellg0ld\Museum\commands\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\forms\faction\MemberForm;
use Steellg0ld\Museum\forms\FactionForm;
use Steellg0ld\Museum\utils\Utils;
use Steellg0ld\Museum\base\Faction as FactionAPI;

class Faction extends Command {
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            if(isset($args[0])){
                if($sender->hasFaction()){
                    switch ($args[0]){
                        case "create":
                            if($sender->hasFaction()){
                                FactionForm::createForm($sender);
                            }else{
                                FactionForm::manage($sender);
                            }
                            break;
                        case "invite":
                            MemberForm::invite($sender);
                            break;
                        case "accept":
                            var_dump(FactionAPI::$invitations[$sender->getName()]);
                            FactionAPI::acceptInvitation($sender);
                            break;
                        case "deny":
                            FactionAPI::denyInvitation($sender);
                            break;
                        case "h":
                        case "home":
                            break;
                        case "seth":
                        case "sethome":
                        case "sh":

                            break;
                    }
                }else{

                }
            }else{
                Utils::sendMessage($sender,"FACTION_NO_ARGUMENTS");
            }
        }
    }
}