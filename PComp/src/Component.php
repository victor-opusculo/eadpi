<?php
namespace VictorOpusculo\PComp;

require_once "IRenderable.php";

abstract class Component implements IRenderable
{
	public function __construct(...$properties)
	{
		if (!empty($properties))
			foreach ($properties as $p => $v)
            {
				if (isset($v))
					$this->$p = $v;
            }
	}

	public function prepareSetUp() : void
	{
		$this->setUp();
		$this->markupComps = $this->markup();
		
		$this->prepareSetupForArrays($this->markupComps);
		if ($this->prepareSetUpForChildren) $this->prepareSetupForArrays($this->children);
	}

	protected function prepareSetupForArrays(&$markupSymbol) : void
	{
		if ($markupSymbol instanceof Component)
			$markupSymbol->prepareSetUp();
		else if (is_array($markupSymbol))
		{
			foreach ($markupSymbol as $value)
				$this->prepareSetupForArrays($value);
		}	
	}
	
	protected function setUp() { }

	protected Component|array|null $markupComps;
	protected bool $prepareSetUpForChildren = false;

	public array|Component $children = [];
	
	public function renderToString() : string
	{
		ob_start();
		$this->render();
		$output = ob_get_contents();
		ob_end_clean();
		return $output;
	}
	
	protected abstract function markup() : Component|array|null;

	public function render() : void
	{
		Prelude\render
		([
			$this->markupComps	
		]);
	}

	private static function flattenArray(array $demo_array) : array
    {
        $new_array = array();
        array_walk_recursive($demo_array, function($array) use (&$new_array) { $new_array[] = $array; });
        return $new_array;
    }
	
	protected function renderChildren()
	{
		$allChilds = $this->flattenArray(is_array($this->children) ? $this->children : [ $this->children ]);

		if (!empty($allChilds))
			foreach ($allChilds as $child)
				if ($child instanceof IRenderable)
					$child->render();
					
	}
}