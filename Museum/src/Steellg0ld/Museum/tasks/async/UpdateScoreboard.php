<?php

namespace Steellg0ld\Museum\tasks\async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use Steellg0ld\Museum\base\Player;

class UpdateScoreboard extends AsyncTask {

    public function __construct() {

    }

    public function onRun() {

    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server) {
        foreach ($server->getOnlinePlayers() as $player){
            if($player instanceof Player){
                $player->setScoreboard();
            }
        }
    }
}