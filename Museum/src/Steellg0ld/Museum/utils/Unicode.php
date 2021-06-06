<?php

namespace Steellg0ld\Museum\utils;

use Steellg0ld\Museum\Plugin;

class Unicode {
    const GRID = 16;

    public static function init(){
        $config = Plugin::getInstance()->getConfigFile("cc");

        $GRID = 16;
        $filename = basename("glyph_E1", ".png");
        $startChar = hexdec(substr($filename, strrpos($filename, "_") + 1) . "00");
        $g = 0;
        $i = 0;
        do {
            $g++;
            $ci = $startChar + $i;//char index
            $char = mb_chr($ci);
            $vv[$g] = $char;
        } while (++$i < $GRID ** 2);

        for ($a = 1; ; $a++) {
            if ($a > 119) {
                break;
            }
            $config->set($a, $vv[$a]);
            $config->save();
        }
    }

