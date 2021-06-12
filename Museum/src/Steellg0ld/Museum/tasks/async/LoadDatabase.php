<?php

namespace Steellg0ld\Museum\tasks\async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;
use Steellg0ld\Museum\base\Faction;
use Steellg0ld\Museum\Plugin;

class LoadDatabase extends AsyncTask {

    private $db;

    public function __construct() {
        $this->db = array(Plugin::getInstance()->getDataFolder() . Plugin::FILE_DB);
    }

    public function onRun() {
        $results = [];

        $db = new \SQLite3($this->db[0]);
        $data = $db->query("SELECT faction, players, power, money, allies, description, claim_message, claims, roles, dates, invests, home FROM faction");
        while ($resultArr = $data->fetchArray(SQLITE3_ASSOC)) {
            $results["faction"][$resultArr['faction']] = array(unserialize(base64_decode($resultArr['players'])), $resultArr['power'], $resultArr['money'], unserialize(base64_decode($resultArr['allies'])), base64_decode($resultArr['description']), base64_decode($resultArr['claim_message']), unserialize(base64_decode($resultArr['claims'])), unserialize(base64_decode($resultArr['roles'])), unserialize(base64_decode($resultArr['dates'])), unserialize(base64_decode($resultArr['invests'])), unserialize(base64_decode($resultArr['home'])));
        }
        $this->setResult($results);
    }

    /**
     * @param Server $server
     */
    public function onCompletion(Server $server) {
        $result = $this->getResult();

        if(!empty($result)) {
            if (isset($result["faction"])) {
                foreach ($result["faction"] as $key => $array) {
                    Faction::$factions[$key] = array("players" => $array[0], "power" => $array[1], "money" => $array[2], "allies" => $array[3], "description" => $array[4], "claim_message" => $array[5], "roles" => $array[7], "dates" => $array[8], "invests" => $array[9], "home" => $array[10]);
                    Faction::$claims[$key] = $array[6];
                    var_dump($array[9]);
                }
            }
        }
    }
}