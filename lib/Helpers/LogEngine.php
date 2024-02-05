<?php
namespace VictorOpusculo\Eadpi\Lib\Helpers;

define('LOGS_PATH', __DIR__ . "/../../log");

final class LogEngine
{
	public static function writeLog($actionMessage)
	{
		if (!empty($actionMessage))
		{
			$userEmail = $_SESSION['user_email'] ?? "Anônimo";
		
			$logData =
			[
				date("d/m/Y H:i:s"),
				"IP: " . $_SERVER['REMOTE_ADDR'],
				"Usuário: " . $userEmail,
				$actionMessage
			];
			
			$logStringEntry = implode(" | ", $logData) . PHP_EOL;
			
			file_put_contents(LOGS_PATH . "/eadpi_" . date("M-Y") . ".log", $logStringEntry, FILE_APPEND);
		}
	}

	public static function writeErrorLog($actionMessage)
	{
		if (!empty($actionMessage))
			self::writeLog("*** ERRO *** " . $actionMessage);
	}

}