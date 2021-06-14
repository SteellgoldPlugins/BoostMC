<?php

namespace Steellg0ld\Museum\commands\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\Server;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\entity\Wither;
use Steellg0ld\Museum\forms\Emoticons;
use Steellg0ld\Museum\forms\faction\MemberForm;
use Steellg0ld\Museum\forms\FactionForm;
use Steellg0ld\Museum\utils\Utils;
use Steellg0ld\Museum\base\Faction as FactionAPI;

class Test extends Command {
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = [])
    {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            if($args[0] == "1"){
                Emoticons::manage($sender);
            }else{
                Entity::createEntity(Wither::WITHER,$sender->getLevel(),Entity::createBaseNBT($sender));
            }
        }
    }
}