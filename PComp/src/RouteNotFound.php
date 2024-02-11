<?php

namespace VictorOpusculo\PComp;

use VictorOpusculo\PComp\RouteHandler;

class RouteNotFound extends RouteHandler
{
	public function __construct()
	{
		$this->middlewares[] = [ $this, 'middleware' ];
	}

	protected function middleware()
	{	
		header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
		header('Content-Type: text/plain', true, 404);
		echo 'Erro 404!';
		exit;
	}
}