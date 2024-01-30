<?php
namespace VictorOpusculo\Eadpi\Lib\Helpers;

final class System
{
    private function __construct() { }

    public static function systemBaseDir() : string
    {
        return __DIR__ . '/../..';
    } 

    public static function getMailConfigs()
    {
        $configs = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/eadpi_config.ini", true);
        return $configs['regularmail'];
    }

    public static function siteName() : string
    {
        return "EAD do Parlamento de Itapevi";
    }
}