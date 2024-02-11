<?php

namespace VictorOpusculo\PComp;

final class Context
{
    private function __construct() { }

    private static $contextData = [];

    public static function get(string $key)
    {
        if (isset(self::$contextData[$key]))
            return self::$contextData[$key];
        else
            return null;
    }

    public static function &getRef(string $key)
    {
        if (isset(self::$contextData[$key]))
            return self::$contextData[$key];
        else
            return null;
    }

    public static function set(string $key, $value)
    {
        self::$contextData[$key] = $value;
    }
}