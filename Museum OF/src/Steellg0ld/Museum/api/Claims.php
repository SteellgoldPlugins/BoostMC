<?php

namespace Steellg0ld\Museum\api;

use pocketmine\level\Level;
use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;
use Steellg0ld\Museum\Plugin;

class Claims
{
    public static array $claims = [];

    public function initClaim() {
        foreach (MFaction::getAllFactions() as $faction) {
            $fac = MFaction::getDataByIdentifier($faction["identifier"]);
            self::$claims[$faction["identifier"]] = unserialize(base64_decode($fac['claims']));
        }
    }

    /**
     * @param MPlayer $player
     * @param String $faction
     */
    public function claim(MPlayer $player, String $faction, string $message = null){
        $chunk = $player->getLevel()->getChunkAtPosition($player);
        $chunkX = $chunk->getX();
        $chunkZ = $chunk->getZ();
        $world = $player->getLevel()->getFolderName();
        $claims = self::$claims[$faction];
        array_push($claims, "{$chunkX}:{$chunkZ}:{$world}");
        self::$claims[$faction] = $claims;

        $config = Plugin::getInstance()->getClaimsMessages();
        if($message !== null){
            $config->set("{$chunkX}:{$chunkZ}:{$world}", $message);
        }else{
            $config->set("{$chunkX}:{$chunkZ}:{$world}", $faction);
        }
        $config->save();
    }

    /**
     * @param MPlayer $player
     * @param string $faction
     */
    public function unclaim(MPlayer $player, string $faction) {
        $chunk = $player->getLevel()->getChunkAtPosition($player);
        $chunkX = $chunk->getX();
        $chunkZ = $chunk->getZ();
        $world = $player->getLevel()->getFolderName();
        $claim = self::$claims[$faction];
        unset($claim[array_search("{$chunkX}:{$chunkZ}:{$world}", $claim)]);
        self::$claims[$faction] = $claim;
    }

    /**
     * @param Level $level
     * @param int $chunkX
     * @param int $chunkZ
     * @return bool
     */
    public function isInClaim(Level $level, int $chunkX, int $chunkZ): bool {
        $world = $level->getFolderName();
        $array = [];
        foreach (self::$claims as $faction => $claims) {
            $array = array_merge($array, $claims);
        }
        return in_array("{$chunkX}:{$chunkZ}:{$world}", $array);
    }

    /**
     * @param Level $level
     * @param int $chunkX
     * @param int $chunkZ
     * @return string
     */
    public function getFactionClaim(Level $level, int $chunkX, int $chunkZ): string {
        $world = $level->getFolderName();
        foreach (self::$claims as $faction => $claims) {
            foreach ($claims as $claim) {
                if ($claim === "{$chunkX}:{$chunkZ}:{$world}") return $faction;
            }
        }
        return "";
    }

    /**
     * @param string $faction
     * @return int
     */
    public static function getClaimCount(string $faction): int {
        return count(self::$claims[$faction])?? 0;
    }

    /**
     * @return array
     */
    public function getClaims(): array
    {
        return self::$claims;
    }
}