<?php
namespace VictorOpusculo\Eadpi\Components\Data;

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;

class DataGridIcon implements DataGridCellValue
{
	private string $file;
	private string $altText;
	private string $title;
	public $textAfterIcon = "";
	public ?int $width;
	public ?int $height;
	
	public function __construct(string $filePathFromSystemDir, string $altText, string $title = null, int $width = null, int $height = null)
	{
		$this->file = $filePathFromSystemDir;
		$this->altText = $altText;
		$this->title = $title ?? $altText;
		$this->width = $width;
		$this->height = $height;
	}
	
	public function generateHtml() : string
	{
		$widthAndHeightHTML = "";
		if ($this->width)
			$widthAndHeightHTML .= ' width="' . $this->width . '" ';
		
		if ($this->height)
			$widthAndHeightHTML .= ' height="' . $this->height . '" ';

		return '<img style="vertical-align: middle;" src="' . URLGenerator::generateFileURL($this->file) . '" alt="' . $this->altText . '" title="' . $this->title . '" ' . $widthAndHeightHTML . '/> ' . $this->textAfterIcon;
	}
}