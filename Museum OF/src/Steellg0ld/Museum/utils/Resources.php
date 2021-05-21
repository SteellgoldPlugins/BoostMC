<?php

namespace Steellg0ld\Museum\utils;

use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\NamedTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\BinaryStream;
use Steellg0ld\Museum\Plugin;

class Resources
{
    /**
     * @param $bytes
     * @return int[]|null
     */
    public static function getSize($bytes) : ?array
    {
        switch(strlen($bytes)){
            case 64*64*4:
                $l = 64;
                $L = 64;
                return [$l, $L];
            case 64*32*4:
                $l = 64;
                $L = 32;
                return [$l, $L];
            case 128*128*4:
                $l = 128;
                $L = 128;
                return [$l, $L];
            default :
                return null;
        }
    }

    /**
     * @param $bytes
     * @return false|\GdImage|resource
     */
    public static function getHeadBYTEStoIMG($bytes)
    {
        $size = self::getSize($bytes);
        $l = $size[0] / 8;
        $L = $size[1] / 8;
        $img = self::BYTEStoIMG($bytes);
        $crop = @imagecrop($img, ['x' => $L, 'y' => $L, 'width' => $L, 'height' => $l]);
        @imagedestroy($img);
        return $crop;
    }

    /**
     * @param string $image
     * @return string
     */
    public static function PNGtoBYTES(string $image): string
    {
        $path = Plugin::getInstance()->getDataFolder() . "skins/image/" . $image . ".png";
        $img = @imagecreatefrompng($path);
        $bytes = '';

        $L = (int) @getimagesize($path)[0];
        $l = (int) @getimagesize($path)[1];

        for ($y = 0; $y < $l; $y++) {
            for ($x = 0; $x < $L; $x++) {
                $rgba = @imagecolorat($img, $x, $y);
                $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;
                $bytes .= chr($r) . chr($g) . chr($b) . chr($a);
            }
        }
        @imagedestroy($img);
        return $bytes;
    }

    /**
     * @param string $bytes
     * @return false|\GdImage|resource
     */
    public static function BYTEStoIMG(string $bytes)
    {
        $size = self::getSize($bytes);
        $l = $size[0];
        $L = $size[1];

        $img = @imagecreatetruecolor($l, $L);
        @imagealphablending($img, false);
        @imagesavealpha($img, true);

        $stream = new BinaryStream($bytes);
        for($y = 0; $y < $L; ++$y){
            for($x = 0; $x < $l; ++$x){
                $r = $stream->getByte();
                $g = $stream->getByte();
                $b = $stream->getByte();
                $a = 127 - (int) floor($stream->getByte() / 2);
                $colour = @imagecolorallocatealpha($img, $r, $g, $b, $a);
                @imagesetpixel($img, $x, $y, $colour);
            }
        }
        return $img;
    }

    /**
     * @param string $name
     * @return string
     */
    public static function getGeometry(string $name): string
    {
        $geometry = file_get_contents(Plugin::getInstance()->getDataFolder() . "skins/geometry/" . $name . ".json");
        return $geometry;
    }

    /**
     * @param string $image
     * @param string $bytes
     * @return string
     */
    public static function overlayingSkin(string $image, string $bytes): string
    {
        $L = (int) self::getSize($bytes)[0];
        $l = (int) self::getSize($bytes)[1];

        $img = self::BYTEStoIMG($bytes);

        $path = Plugin::getInstance()->getDataFolder() . "skins/image/" . $image .".png";

        $skin = @imagecreatefrompng($path);

        $bytes = "";

        for ($y = 0; $y < $l; $y++) {

            for ($x = 0; $x < $L; $x++) {

                $rgba = @imagecolorat($skin, $x, $y);
                $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                $r = ($rgba >> 16) & 0xff;
                $g = ($rgba >> 8) & 0xff;
                $b = $rgba & 0xff;

                if ($g == 255 and $r == 0 and $b == 27 and $a == 254) { //IDK for 254

                    $rgba = @imagecolorat($img, $x, $y);
                    $a = ((~((int)($rgba >> 24))) << 1) & 0xff;
                    $r = ($rgba >> 16) & 0xff;
                    $g = ($rgba >> 8) & 0xff;
                    $b = $rgba & 0xff;

                }

                $bytes .= chr($r) . chr($g) . chr($b) . chr($a);

            }

        }

        @imagedestroy($skin);
        @imagedestroy($img);

        return $bytes;
    }

    /**
     * @return CompoundTag
     */
    public static function getSkinTag() : CompoundTag
    {
        $skin = str_repeat("\x00", 8192);
        return new CompoundTag("Skin", [
            new StringTag("Name", "Standard_Custom"),
            new ByteArrayTag("Data", $skin)
        ]);
    }

    /**
     * @param int $l
     * @param int $L
     * @param string $img
     * @return string
     */
    public static function getSkinNameWhitSize(int $l, int $L, string $img) : string{
        return $img . $l . "x" . $L;
    }

    /**
     * @param string $image
     * @return bool
     */
    public static function getFile(string $image) : bool{
        return file_exists(Plugin::getInstance()->getDataFolder() . "skins/image/" . $image .".png");
    }
}