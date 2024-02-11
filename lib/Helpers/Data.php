<?php
namespace VictorOpusculo\Eadpi\Lib\Helpers;

final class Data
{
    public function __construct() { }

    public static function getMailConfigs()
    {
        $configs = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/eadpi_config.ini", true);
        return $configs['regularmail'];
    }

    public static function truncateText(?string $string, int $maxLength) : string
    {
        if (!$string) return '';
        return mb_strlen($string) > $maxLength ? mb_substr($string, 0, $maxLength) . '...' : $string;
    }

    public static function hsc(?string $string) : string
    {
        return htmlspecialchars($string ?? '', ENT_NOQUOTES);
    }

    public static function hscq(?string $string) : string
    {
        return htmlspecialchars($string ?? '', ENT_QUOTES);
    }

    public static function flattenArray(array $demo_array) : array
    {
        $new_array = array();
        array_walk_recursive($demo_array, function($array) use (&$new_array) { $new_array[] = $array; });
        return $new_array;
    }

    public static function transformDataRows(array $input, array $rules) : array
    {
        $output = [];
        
        if ($input)
            foreach ($input as $row)
            {
                $newRow = [];
                foreach ($rules as $newKeyName => $ruleFunction)
                    $newRow[$newKeyName] = $ruleFunction($row);

                $output[] = $newRow;
            }
            
        return $output;
    }
} 