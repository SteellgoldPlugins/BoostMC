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
        $data = $db->query("SELECT faction, players, power, money, allies, description, claim_message, claims FROM faction");
        while ($resultArr = $data->fetchArray(SQLITE3_ASSOC)) {
            $results["faction"][$resultArr['faction']] = array(unserialize(base64_decode($resultArr['players'])), $resultArr['power'], $resultArr['money'], unserialize(base64_decode($resultArr['allies'])), base64_decode($resultArr['description']), base64_decode($resultArr['claim_message']), unserialize(base64_decode($resultArr['claims'])));
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
                    Faction::$factions[$key] = array("players" => array_unique($array[0]), "power" => $array[1], "money" => $array[2], "allies" => $array[3]);
                    Faction::$claims[$key] = $array[4];
                }
            }
        }
    }
}