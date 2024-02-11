<?php
namespace VictorOpusculo\PComp;

final class ApiInitializer
{
	public function __construct(array $routesTree, string $routeQueryParam = "route")
	{
		$urlPath = strtolower($_GET[$routeQueryParam] ?? '');

		if ($urlPath && $urlPath[0] == '/')
			$urlPath = substr($urlPath, 1);

		if (!$urlPath)
			$urlPath = '/';

		if ($urlPath && $urlPath[strlen($urlPath) - 1] !== '/')
			$urlPath .= '/';

		$paths = explode('/', $urlPath);

		$routeClass = null;

		$currentNamespace = $routesTree;
		$finalRoutePaths = [];
		$matches = [];

		$routesStatus = array_fill(0, count($paths), null);
		foreach ($paths as $pIndex => $path)
		{
			if (array_key_exists('/' . $path, $currentNamespace))
			{
				if (is_callable($currentNamespace['/' . $path]))
				{
					$routesStatus[$pIndex] = true;
					$currentNamespace = ($currentNamespace['/' . $path])();
					$finalRoutePaths[] = $path;
				}
				else
				{
					$routesStatus[$pIndex] = true;
					$routeClass = $currentNamespace['/' . $path];
					$finalRoutePaths[] = $path;
					$currentNamespace = [];
				}
			}
			else
			{
				foreach (array_keys($currentNamespace) as $key)
					if (preg_match('/\/\[\w+\]/', $key) !== 0)
					{
						if (is_callable($currentNamespace[$key]))
						{
							$routesStatus[$pIndex] = true;
							$currentNamespace = ($currentNamespace[$key])();
							$finalRoutePaths[] = $key;
							$matches[] = $path;
						}
						else
						{
							
							$routesStatus[$pIndex] = true;
							$routeClass = $currentNamespace[$key];
							$finalRoutePaths[] = $key;
							$matches[] = $path;
							$currentNamespace = [];
							break;
						}
					}
			}

			if (!$routesStatus[$pIndex]) break;
		}

		try
		{		
			$urlParams = null;
			if (!empty($matches) && !empty($routeClass))
			{
				$paramNames = [];
				preg_match_all('/\[(\w+?)\]/', implode('/', $finalRoutePaths ), $paramNames);			
				$urlParams = array_combine($paramNames[1], $matches);
			}

			$params = is_array($urlParams) ? $urlParams : [];

			$route = isset($routeClass) && class_exists($routeClass) ? new $routeClass() : new RouteNotFound();
			$route->run($urlParams);
		}
		catch (\Exception $e)
		{
			header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error", true, 500);
			header('Content-Type: text/plain', true, 500);
			echo 'Erro 500!';
			exit;
		}
	}
}
