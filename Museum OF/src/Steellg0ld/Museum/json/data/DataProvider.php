<?php

namespace Steellg0ld\Museum\json\data;


use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\json\JSONProvider;

class DataProvider
{
    public function createFaction(string $uniqid, MPlayer $owner, string $name, string $description, array $members, int $power){
        $provider = new JSONProvider();
        $data = $provider->getFactionConfig($uniqid);
        $data->set($uniqid, $this->defaultData($uniqid, $owner->getName(), $name, $description, $members, $power));
        $data->save();
    }

    public function defaultData(string $uniqid, String $owner_name = "Server", string $name = "Default Name", string $description = "Default Faction Description", array $members = [], int $power = 20) : array{
        return $default_data = [
            "name" => $name,
            "uniqid" => $uniqid,
            "description" => $description,
            "members" => $members,
            "power" => $power,
            "actions" => [
                1 => [
                    "at" => time(),
                    "content" => "Faction created by ".$owner_name
                ]
            ],
            "upgrades" => [
                "player_slot" => 0,
                "slot_faction_chest" => 0,
                "heal_home_faction" => 0
            ]
        ];
    }
}