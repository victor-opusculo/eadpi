<?php
namespace VictorOpusculo\PComp;

require_once "Component.php";

class HtmlTag extends Component
{
    public function __construct(string $tagName, ...$properties)
    {
        if (!empty($properties))
			foreach ($properties as $p => $v)
            {
                if ($p === 'children')
                    $this->children = $v;
                else
				    $this->properties[$p] = $v;
            }

        $this->tagName = $tagName;
    }

    protected string $tagName;
    protected array $properties = [];
	protected bool $prepareSetUpForChildren = true;

    protected function markup() : Component|array|null
    {
        return null; 
    }

    public function render() : void
    {
        echo "<{$this->tagName} {$this->getPropertiesAsHtml($this->properties)}>";
        $this->renderChildren();
        echo "</{$this->tagName}>";
    }

    protected function getPropertiesAsHtml(array $props) : string
    {
        $out = "";
        foreach ($props as $p => $v)
		{
			if (is_bool($v) && !$v)
				continue;
			
            $out .= "$p=\"$v\" ";
		}
        return $out;
    }


}