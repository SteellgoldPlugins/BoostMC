<?php

namespace Steellg0ld\Museum\commands\defaults;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use Steellg0ld\Museum\base\Database;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\utils\Utils;

class Link extends Command{
    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        if($sender instanceof Player){
            $name = $sender->getName();
            $code = substr(str_shuffle($chars),0,-45);

            if($sender->discordId == "none"){
                $database = new Database();
                $database->getDatabase()->query("INSERT INTO `discord`(`code`, `player`) VALUES ('$code','$name')");
                Utils::sendMessage($sender, "LINK_CODE",["{CODE}"],[$code]);
            }else{
                Utils::sendMessage($sender, "LINK_ALREADY_LINK");
            }
        }
    }
}