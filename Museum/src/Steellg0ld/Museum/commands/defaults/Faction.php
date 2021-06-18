<?php

namespace Steellg0ld\Museum\commands\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\forms\faction\MemberForm;
use Steellg0ld\Museum\forms\FactionForm;
use Steellg0ld\Museum\utils\Unicode;
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
                if(in_array($args[0], ["accept", "deny","map"]) or $sender->hasFaction()){
                    switch ($args[0]){
                        case "create":
                            if(!$sender->hasFaction()) FactionForm::createForm($sender);
                            break;
                        case "map":
                            if(isset($args[1])){
                                switch ($args[1]){
                                    case "on":
                                        Utils::sendMessage($sender,"FACTION_MAP_ENABLED",["{MAP}"],[Unicode::MAP]);
                                        $sender->map = true;
                                        break;
                                    case "off":
                                        Utils::sendMessage($sender,"FACTION_MAP_DISABLED");
                                        $sender->map = false;
                                        break;
                                    default:
                                        $sender->sendMessage(implode(TextFormat::EOL, FactionAPI::getMap($sender)));
                                        break;
                                }
                            }
                            break;
                        case "invite":
                            if(!$sender->hasFaction()) FactionForm::createForm($sender); else MemberForm::invite($sender);
                            break;
                        case "accept":
                            FactionAPI::acceptInvitation($sender);
                            break;
                        case "deny":
                            FactionAPI::denyInvitation($sender);
                            break;
                        case "h":
                        case "home":
                            if(!$sender->hasFaction()) FactionForm::createForm($sender); else FactionAPI::teleportHome($sender);
                            break;
                        case "seth":
                        case "sethome":
                            if(!$sender->hasFaction()){
                                FactionForm::createForm($sender->getFaction());
                            } else {
                                Utils::sendMessage($sender, "FACTION_SETHOME");
                                foreach (FactionAPI::getMembers($sender->getFaction()) as $member){
                                    $member = Server::getInstance()->getPlayer($member);
                                    if(!$member instanceof Player) return;
                                    Utils::sendMessage($member,"FACTION_SETHOME_BROADCAST",["{PLAYER}"], [$sender->getName()]);
                                }

                                FactionAPI::setHome($sender->getFaction(), $sender->getPosition());
                            }
                            break;
                        case "claim":
                            if(!$sender->hasFaction()) FactionForm::createForm($sender->getFaction()); else FactionAPI::claim($sender);
                            break;
                        case "unclaim":
                            if(!$sender->hasFaction()) FactionForm::createForm($sender->getFaction()); else FactionAPI::unclaim($sender);
                            break;
                        case "members":
                        case "players":
                            if(!$sender->hasFaction()){
                                FactionForm::createForm($sender->getFaction());
                            }elseif($sender->faction_role >= FactionAPI::OFFICIER){
                                MemberForm::list($sender);
                            }else{
                                Utils::sendMessage($sender,"MUST_BE_OFFICIER");
                            }
                            break;
                        case "help":
                            // TODO
                            break;
                        case "edit":
                            if(!$sender->hasFaction()){
                                FactionForm::createForm($sender->getFaction());
                            }elseif($sender->faction_role >= FactionAPI::LEADER){
                                FactionForm::openFaction($sender);
                            }else{
                                Utils::sendMessage($sender,"MUST_BE_LEADER");
                            }
                            break;
                        case "leave":
                            FactionAPI::leaveFaction($sender);
                            break;

                    }
                }else{
                    FactionForm::createForm($sender);
                }
            }else{
                if($sender->hasFaction()){
                    FactionForm::openInfo($sender);
                }else{
                    Utils::sendMessage($sender,"FACTION_NO_ARGUMENTS");
                }
            }
        }
    }
}