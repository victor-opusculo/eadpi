<?php

namespace VictorOpusculo\PComp;

use VictorOpusculo\PComp\{View, Component, HeadManager, StyleManager, ScriptManager};

class PageNotFound extends Component
{
	protected function setUp()
	{
		HeadManager::$title = "Erro 404";	
		header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
	}	

	protected function markup() : Component
	{
		return Prelude\tag("div", style: 'padding: 15px; text-align: center;', children: Prelude\text("Página não encontrada!"));
	}
}