<?php

namespace Steellg0ld\Museum\forms;

use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\forms\api\CustomForm;

class ManageForm {
    public static function createFaction(MPlayer $player){
        {
            $form = new CustomForm(
                function (MPlayer $p, $data) {
                    if ($data !== null) {

                    }
                }
            );

            $form->setTitle();
            $player->sendForm($form);
        }
    }
}