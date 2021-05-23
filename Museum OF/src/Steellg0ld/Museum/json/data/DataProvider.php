<?php

namespace Steellg0ld\Museum\json\data;

use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\json\JSONProvider;
use Steellg0ld\Museum\Plugin;

class DataProvider
{
    CONST SLOTS = [
        1 => "3;4;5",
        2 => "6;7;8",
        3 => "9;10;11",
        4 => "12;13;14",
        5 => "15;16;17",
        6 => "18;19;20",
        7 => "21;22;23",
        8 => "24;25;26",
    ];

    public function createFaction(string $uniqid, MPlayer $owner, string $name, string $description, array $members, int $power){
        $provider = new JSONProvider();
        $factions = Plugin::getInstance()->getConfigFile("factions");
        $factions->set($uniqid, $uniqid);
        $factions->save();

        $data = $provider->getFactionConfig($uniqid);
        $data->set($uniqid, $this->defaultData($uniqid, $owner->getName(), $name, $description, $members, $power));
        $data->save();
    }

    public function defaultData(string $uniqid, String $owner_name = "Server", string $name = "Default Name", string $description = "Default Faction Description", array $members = [], int $power = 20) : array{
        return [
            "name" => $name,
            "uniqid" => $uniqid,
            "description" => $description,
            "members" => [
                $owner_name => 3
            ],
            "power" => $power,
            "actions" => [
                1 => [
                    "at" => time(),
                    "content" => "Faction created by ".$owner_name
                ]
            ],
            "claims" => "YTowOnt9",
            "upgrades" => [
                "player_slot" => 0,
                "slot_faction_chest" => 0,
                "heal_home_faction" => 0
            ],
            "invitations_dates" => [
                $owner_name => time()
            ]
        ];
    }
}