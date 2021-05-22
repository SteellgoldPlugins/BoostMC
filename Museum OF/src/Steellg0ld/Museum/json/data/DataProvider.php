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
            "chest" => "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",
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