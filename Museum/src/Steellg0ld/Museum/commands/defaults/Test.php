<?php

namespace Steellg0ld\Museum\commands\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\ItemEntity;
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
            }elseif($args[0] == "2"){
                $i = 0;
                $e = 0;
                foreach(Server::getInstance()->getLevels() as $level){
                    foreach($level->getEntities() as $entity){
                        if($entity instanceof ItemEntity){
                            $entity->close();
                            $i++;
                        }

                        if($entity instanceof ExperienceOrb){
                            $entity->close();
                            $e++;
                        }
                    }
                }

                $sender->sendMessage($i . " items ont étées supprimées");
                $sender->sendMessage($e . " orbes d'experience ont étées supprimées");
            }else{
                Entity::createEntity(Wither::WITHER,$sender->getLevel(),Entity::createBaseNBT($sender));
            }
        }
    }
}