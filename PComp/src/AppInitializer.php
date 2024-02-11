<?php
namespace VictorOpusculo\PComp;

use VictorOpusculo\PComp\{View, Component, HeadManager, StyleManager, ScriptManager};

final class AppInitializer
{
	public function __construct(array $routesTree, string $pageQueryParam = "page")
	{
		
		$urlPath = strtolower($_GET[$pageQueryParam] ?? '');

		if ($urlPath && $urlPath[0] == '/')
			$urlPath = substr($urlPath, 1);

		if (!$urlPath)
			$urlPath = '/';

		if ($urlPath && $urlPath[strlen($urlPath) - 1] !== '/')
			$urlPath .= '/';

		$paths = explode('/', $urlPath);

		$pageClass = null;

		$currentNamespace = $routesTree;
		$finalRoutePaths = [];
		$matches = [];

		$layouts = [];
		$errorPages = [];

		$routesStatus = array_fill(0, count($paths), null);
		foreach ($paths as $pIndex => $path)
		{
			if (array_key_exists('__layout', $currentNamespace) && !in_array($currentNamespace['__layout'], $layouts))
				$layouts[] = $currentNamespace['__layout'];

			if (array_key_exists('__error', $currentNamespace) && !in_array($currentNamespace['__error'], $layouts))
				$errorPages[] = $currentNamespace['__error'];

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
					$pageClass = $currentNamespace['/' . $path];
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
							$pageClass = $currentNamespace[$key];
							$finalRoutePaths[] = $key;
							$matches[] = $path;
							$currentNamespace = [];
							break;
						}
					}
			}

			if (!$routesStatus[$pIndex]) break;
	
		}
		
		$this->pageMessages = !empty($_GET['messages']) ? explode('//', $_GET['messages']) : [];
		\VictorOpusculo\PComp\Context::set('page_messages', $this->pageMessages);
		
		try
		{	
		
			$urlParams = null;
			if (!empty($matches) && !empty($pageClass))
			{
				$paramNames = [];
				preg_match_all('/\[(\w+?)\]/', implode('/', $finalRoutePaths ), $paramNames);			
				$urlParams = array_combine($paramNames[1], $matches);
			}

			$params = is_array($urlParams) ? $urlParams : [];

			$page = isset($pageClass) && class_exists($pageClass) ? 
				$this->setupLayoutsAndPage($layouts, $pageClass, $params)
				:
				$this->setupLayoutsAndPage($layouts, PageNotFound::class, $params);

			$page->prepareSetUp();

			$this->mainFrameComponents = [ $page ];
		}
		catch (\Exception $e)
		{
			$lastErrPage = array_pop($errorPages);
			$page = isset($lastErrPage) && class_exists($lastErrPage) ? 
			$this->setupLayoutsAndPage($layouts, $lastErrPage, [ 'exception' => $e ])
			:
			$this->setupLayoutsAndPage($layouts, PageNotFound::class, $params);

			$page->prepareSetUp();

			$this->mainFrameComponents = [ $page ];
		}
	}
	
	public array $pageMessages;
	public array $mainFrameComponents;
	
	private function setupSingleLayout(string $currentLayoutClassName, array $nextLayoutClassNames, array $params) : Component
	{
		$next = array_shift($nextLayoutClassNames);
		if (isset($next))
			return new $currentLayoutClassName( ...$params, children: [ $this->setupSingleLayout($next, $nextLayoutClassNames, $params) ]);
		else
			return new $currentLayoutClassName( ...$params);
	}


	private function setupLayoutsAndPage(array $layoutsList, string $page, array $params) : Component
	{
		$layoutsAndPage = array_merge($layoutsList, [ $page ]);

		$first = array_shift($layoutsAndPage);
		return $this->setupSingleLayout($first, $layoutsAndPage, $params);
	}
}
