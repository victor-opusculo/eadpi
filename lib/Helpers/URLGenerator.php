<?php
namespace VictorOpusculo\Eadpi\Lib\Helpers;

final class URLGenerator
{
	private function __construct() { }
	
	public static ?bool $useFriendlyUrls = null;
	public const BASE_URL = '/eadpi';
	
	public static function loadConfigs() : void
	{
		if (isset(self::$useFriendlyUrls)) return;

		$configs = parse_ini_file($_SERVER['DOCUMENT_ROOT'] . "/eadpi_config.ini", true);
		self::$useFriendlyUrls = (bool)($configs['urls']['usefriendly']);
	}

	public static function generatePageUrl(string $pagePath, array $query = []) : string
	{
		self::loadConfigs();
		$qs = count($query) > 0 ? (self::$useFriendlyUrls ? '?' : '&') . self::generateQueryString($query) : '';
		return match (self::$useFriendlyUrls)
		{
			true => self::BASE_URL . ($pagePath[0] == '/' ? $pagePath . $qs : '/' . $pagePath . $qs),
			false => self::BASE_URL . "/index.php?page=$pagePath$qs"
		};
	}
	
	public static function generateFileUrl(string $filePathFromRoot, array $query = []) : string
	{
		self::loadConfigs();

		$qs = count($query) > 0 ? '?' . self::generateQueryString($query) : '';
		return match (self::$useFriendlyUrls)
		{
			true => self::BASE_URL . "/--file/$filePathFromRoot" . $qs,
			false => self::BASE_URL . ($filePathFromRoot[0] == '/' ? '/' . mb_substr($filePathFromRoot, 1) . $qs : '/' . $filePathFromRoot . $qs)
		};
	}
	
	public static function generateScriptUrl(string $filePathFromScriptDir, array $query = []) : string
	{
		self::loadConfigs();

		$qs = count($query) > 0 ? '?' . self::generateQueryString($query) : '';
		return match (self::$useFriendlyUrls)
		{
			true => self::BASE_URL . "/--script/$filePathFromScriptDir" . $qs,
			false => self::BASE_URL . ($filePathFromScriptDir[0] == '/' ? "/script$filePathFromScriptDir" . $qs : "/script/$filePathFromScriptDir" . $qs)
		};
	}

	public static function generateApiUrl(string $apiPath, array $query = []) : string
	{
		self::loadConfigs();
		$qs = count($query) > 0 ? (self::$useFriendlyUrls ? '?' : '&') . self::generateQueryString($query) : '';
		return match (self::$useFriendlyUrls)
		{
			true => self::BASE_URL . '/--api' . ($apiPath[0] == '/' ? $apiPath . $qs : '/' . $apiPath . $qs),
			false => self::BASE_URL . "/api.php?route=$apiPath$qs"
		};
	}

	public static function getHttpProtocolName() : string
	{
		$isHttps = $_SERVER['HTTPS'] ?? $_SERVER['REQUEST_SCHEME'] ?? $_SERVER['HTTP_X_FORWARDED_PROTO'] ?? null;
		$isHttps = $isHttps && (strcasecmp('on', $isHttps) == 0 || strcasecmp('https', $isHttps) == 0);
		return $isHttps ? 'https' : 'http';
	}

	private static function generateQueryString(array $queryData) : string
	{
		return http_build_query($queryData);
	}
}