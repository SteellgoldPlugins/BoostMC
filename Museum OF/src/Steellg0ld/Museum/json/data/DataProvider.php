<?php

namespace Steellg0ld\Museum\json\data;


class DataProvider
{
    public $factions = [];

    public function addFaction(string $faction, array $array){
        $this->factions[$faction] = $array;
    }

    /**
     * @return array
     */
    public function getFactions(): array{
        return $this->factions;
    }

    public function getFaction(string $faction) : array {
        return $this->factions[$faction];
    }

    public function updateFaction(string $faction, array $array){
        $this->factions[$faction] = $array;
    }
}