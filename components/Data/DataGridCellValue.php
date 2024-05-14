<?php
namespace VictorOpusculo\Eadpi\Components\Data;

interface DataGridCellValue
{
    public function generateHtml() : string;
}