<?php

namespace Steellg0ld\Museum\base;

use Steellg0ld\Museum\Plugin;

class Faction{
    public static array $factions = [];
    public static array $claims = [];

    public const RECRUE = 0;
    public const MEMBER = 1;
    public const OFFICIER = 2;
    public const LEADER = 3;

    public const DEFAULT_POWER = 0;
    public const POWER_PER_KILL = 5;
    public const POWER_PER_DEATHS = 10;
    public const INVITATION_EXPIRATION_TIME = 60;
}