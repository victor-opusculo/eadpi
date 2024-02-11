<?php
namespace VictorOpusculo\PComp;

require_once "HtmlTag.php";

class HtmlSelfClosingTag extends HtmlTag
{
    public function __construct(string $tagName, ...$properties)
    {
        parent::__construct($tagName, ...$properties);
    }
    
	protected bool $prepareSetUpForChildren = true;

    public function render() : void
    {
        echo "<{$this->tagName} {$this->getPropertiesAsHtml($this->properties)}/>";
    }
}