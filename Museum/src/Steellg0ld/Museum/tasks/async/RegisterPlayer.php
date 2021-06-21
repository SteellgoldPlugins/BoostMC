<?php

namespace Steellg0ld\Museum\tasks\async;

use pocketmine\scheduler\AsyncTask;
use Steellg0ld\Museum\base\Database;
use Steellg0ld\Museum\base\Player;
use Steellg0ld\Museum\Plugin;

class RegisterPlayer extends AsyncTask {

    private String $player;
    private String $address;

    public function __construct(String $player, String $address) {
        $this->player = $player;
        $this->address = $address;
    }

    public function onRun() {
        $db = new Database();

        $name = $this->player;
        $adress = base64_encode(base64_encode(base64_encode($this->address)));
        $settings = base64_encode(serialize([
            "player_status" => 1,
            "economy_symbol" => 1,
            "unicode" => 0,
            "armor_pvp_status" => false,
            "coords" => true
        ]));
        $db->getDatabase()->query("INSERT INTO players (player, address, faction, role, rank, money, lang, settings,discordId) VALUES ('$name', '$adress', 'none', 0, 0, 0, 'fr_FR', '$settings','none')");
    }
}