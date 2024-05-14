<?php
namespace VictorOpusculo\Eadpi\Components\Data;

class DataGridHtmlCustomElement implements DataGridCellValue
{
	private string $tagName;
	private ?array $attributes;
	private ?DataGridCellValue $content;
	private bool $selfClosing;

	public function __construct(string $tagName, ?array $attributes, ?DataGridCellValue $content, bool $selfClosing = false)
	{
		$this->tagName = $tagName;
		$this->attributes = $attributes;
		$this->content = $content;
		$this->selfClosing = $selfClosing;
	}

	public function generateHtml(): string
	{
		$attrs = "";
		if (isset($this->attributes))
			foreach ($this->attributes as $name => $value)
			{
				$attrs .= "$name=\"$value\" ";
			}

		if (!$this->selfClosing)
			return "<{$this->tagName} $attrs >" . (isset($this->content) ? $this->content->generateHTML() : '') . "</{$this->tagName}>";
		else
			return "<{$this->tagName} $attrs />";

	}
}