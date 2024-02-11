<?php
namespace VictorOpusculo\PComp;

final class StyleManager
{
    private function __construct() { }

    private static array $styles = [];
    
    public static function registerStyle(string $htmlId, string $css) : void
    {
        self::$styles[$htmlId] = $css;
    }

    public static function getStylesText() : string
    {
		$finalCss = "";
		foreach (self::$styles as $id => $css)
			$finalCss .= "<style id=\"$id\">$css</style>";
			
		return $finalCss;
    }
}