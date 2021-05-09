<?php

namespace Steellg0ld\Museum\commands\faction\subcommands;

use Steellg0ld\Museum\base\MFaction;
use Steellg0ld\Museum\base\MPlayer;

class KickSubCommand
{
    public function execute(MPlayer $toKick, MFaction $faction, array $kickInformations)
    {
        $faction->removeMember($toKick);
    }
}