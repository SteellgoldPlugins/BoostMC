<?php

namespace Steellg0ld\Museum\forms;

use FormAPI\CustomForm;
use Steellg0ld\Museum\base\Economy;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\base\Ranks;
use Steellg0ld\Museum\utils\Utils;

class PlayerForm {
    public static function member(Player $player, Player $sender)
    {
        {
            $form = new CustomForm(
                function (Player $p, $data) use ($sender) {
                    if ($data !== null) {
                        if($sender->isOnline() == true){
                            $sender->rank = $data[1];
                            $sender->money = (int)$data[2];
                            Utils::sendMessage($p,"PLAYER_MANAGE_EDITION",["{PLAYER}"],[$sender->getName()]);
                        }else{
                            Utils::sendMessage($p,"PLAYER_DISCONNECTED", ["{PLAYER_DISCONNECTED}"], [$sender->getName()]);
                        }
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "MANAGE_PLAYER_TITLE_FORM",["{PLAYER_MANAGE}"],[$sender->getName()]));
            $form->addLabel(Utils::getMessage($player, "MANAGE_PLAYER_LABEL_FORM",["{RANK}", "{PLAYER_MANAGE}"], [Ranks::translate($player, $sender->getRank()),$sender->getName()]));
            $form->addDropdown("Grade", ["Joueur", "VIP", "VIP+", "Youtube", "Twitch", "Staff", "Administrateur"],$sender->getRank());
            $form->addInput("Argent",$sender->money,$sender->money);
            $form->addToggle("Bannir (Fenêtre externe)");
            $player->sendForm($form);
        }
    }

    public static function ban(Player $player, Player $sender)
    {
        {
            $form = new CustomForm(
                function (Player $p, $data) use ($sender) {
                    if ($data !== null) {
                    }
                }
            );

            $form->setTitle(Utils::getMessage($player, "MANAGE_PLAYER_BAN_TITLE_FORM"));
            $form->addLabel(Utils::getMessage($player, "MANAGE_PLAYER_BAN_LABEL_FORM"));
            $form->addDropdown("Type", ["Jours", "Heures", "Minutes"]);
            $form->addSlider("Temps", 1, 30,$sender->money);
            $form->addToggle("Définitivement");
            $player->sendForm($form);
        }
    }
}