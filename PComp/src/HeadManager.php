<?php
namespace VictorOpusculo\PComp;

final class HeadManager
{
    private function __construct() { }

    public static string $title = "";

    public static function getHeadText() : string
    {
        return "<title>" . htmlspecialchars(self::$title, ENT_QUOTES, 'utf-8') . "</title>";
    }
}