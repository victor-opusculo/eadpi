<?php
namespace VictorOpusculo\Eadpi\Lib\Helpers;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;

abstract class QueryString
{	
	private static function filterParamExcept($paramAndValue, $exceptions)
	{
		$keyValuePair = explode("=", $paramAndValue);
		if (empty($keyValuePair[0]) || empty($keyValuePair[1])) return false;
		
		return array_search($keyValuePair[0], $exceptions) === false ? true : false;
	}
	
	public static function formatNew($name, $value, $toBeAppendedToCurrentQueryString = true)
	{
		if ($toBeAppendedToCurrentQueryString && strlen($_SERVER["QUERY_STRING"]) > 0)
			return "&" . $name . "=" . $value;
		else
			return $name . "=" . $value;
	}
	
	public static function getQueryStringForHtmlExcept()
	{
		$exceptions = func_get_args();
		
		if (URLGenerator::$useFriendlyUrls)
		{
			$exceptions[] = "cont";
			$exceptions[] = "action";
		}
		
		$parametersWithValue = explode("&", $_SERVER["QUERY_STRING"]);
		
		$paramsAndValuesFiltered = array_filter($parametersWithValue, function($paramAndValue) use ($exceptions)
		{
			return self::filterParamExcept($paramAndValue, $exceptions);
		});
		
		$finalParamsAndValues = array_map( function($paramAndValue)
		{
			$keyValuePair = explode("=", $paramAndValue);
			return $keyValuePair[0] . "=" . htmlspecialchars($keyValuePair[1], ENT_QUOTES);
		}, $paramsAndValuesFiltered);
		
		return implode("&", array_unique($finalParamsAndValues));
	}

	public static function getQueryStringExcept()
	{
		$exceptions = func_get_args();
		
		if (URLGenerator::$useFriendlyUrls)
		{
			$exceptions[] = "page";
		}
		
		$parametersWithValue = explode("&", $_SERVER["QUERY_STRING"]);
		
		$finalParamsAndValues = array_filter($parametersWithValue, function($paramAndValue) use ($exceptions)
		{
			return self::filterParamExcept($paramAndValue, $exceptions);
		});
		
		return implode("&", array_unique($finalParamsAndValues));
	}
}