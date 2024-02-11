<?php
namespace VictorOpusculo\PComp;

final class ScriptManager
{
    private function __construct() { }

    private static array $scripts = [];
    
	private static function checkDuplicatedId(string $htmlId) : void
	{
		if (in_array($htmlId, self::$scripts))
			throw new \Exception("ID de HTML de script duplicado!");
	}
	
    public static function registerScript(string $htmlId, string $elementInnerCode, string $src = "", bool $moduleTyped = false) : void
    {
		self::checkDuplicatedId($htmlId);
        self::$scripts[$htmlId] = match ($moduleTyped)
		{
			true => match (true)
			{
				strlen($src) > 0 => "<script id=\"$htmlId\" type=\"module\" src=\"$src\"></script>",
				default => "<script id=\"$htmlId\" type=\"module\">$elementInnerCode</script>"
			},
			false => match (true)
			{
				strlen($src) > 0 => "<script id=\"$htmlId\" src=\"$src\"></script>",
				default => "<script id=\"$htmlId\">$elementInnerCode</script>"
			}
		};
    }

    public static function getScriptText() : string
    {
		return implode("", self::$scripts);
    }
}