<?php
namespace VictorOpusculo\Eadpi\Components\Data;

use VictorOpusculo\Eadpi\Lib\Helpers\Data;

class DataGridText implements DataGridCellValue
{
	private string $string;
	public function __construct(string $string)
	{
		$this->string = $string;
	}
	public function generateHtml(): string
	{
		return nl2br(Data::hsc($this->string));
	}
}