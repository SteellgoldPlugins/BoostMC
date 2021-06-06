<?php

namespace Steellg0ld\Museum\forms;

use FormAPI\CustomForm;
use FormAPI\SimpleForm;
use Steellg0ld\Museum\base\Faction;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\forms\faction\MemberForm;
use Steellg0ld\Museum\Plugin;
use Steellg0ld\Museum\utils\Utils;

class Emoticons{
    public static function manage(Player $player)
    {
        $array = [];
        foreach (Plugin::getInstance()->getConfigFile("cc")->getAll() as $item) {
            array_push($array, $item);
        }

        {
            $form = new SimpleForm(
                function (Player $p, $data) use ($array) {
                    if ($data !== null) {
                        $p->sendMessage($array[$data]);
                    }
                }
            );

            $form->setTitle("Émoticones");
            foreach ($array as $item) {
                $form->addButton($item);
            }
            $player->sendForm($form);
        }
    }
}