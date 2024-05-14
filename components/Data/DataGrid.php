<?php
namespace VictorOpusculo\Eadpi\Components\Data;

use VictorOpusculo\Eadpi\Lib\Helpers\Data;
use VictorOpusculo\PComp\Component;
use function VictorOpusculo\PComp\Prelude\{ tag, rawText, text, scTag };

class DataGrid extends Component
{
    public function setUp()
    {

    }

    protected ?array $dataRows;
    protected ?string $detailsButtonURL = null, $editButtonURL = null, $deleteButtonURL = null;
    protected ?string $selectButtonOnClick = null;
    protected ?string $columnNameAsDetailsButton = null;
    protected ?string $rudButtonsFunctionParamName = 'id';
    protected array $columnsToHide = [];
    protected array $customButtons = [];

	public array $customButtonsParameters = [];

    protected static function formatCellContent($value) : Component
    {
        if ($value instanceof DataGridCellValue)
            return rawText($value->generateHtml());
        else
            return rawText(nl2br(Data::hsc($value)));
    }

    protected static function applyCustomButtonsParameters(string $buttonUrl, array $parametersNamesArray, array $currentDataRow) : string
    {
        $finalUrl = $buttonUrl;
        foreach ($parametersNamesArray as $name => $columnNameOrFixed)
            if ($columnNameOrFixed instanceof FixedParameter)
                $finalUrl = str_replace('{' . $name . '}', (string)$columnNameOrFixed, $finalUrl);
            else if (isset($currentDataRow[$columnNameOrFixed]))
                $finalUrl = str_replace('{' . $name . '}', $currentDataRow[$columnNameOrFixed], $finalUrl);
            else
                $finalUrl = "#";
        return $finalUrl;
    }

    protected function markup(): Component|array|null
    {
        $colCount = 0;
        $colCount2 = 0;

        if (!isset($this->dataRows[0]))
            return text('Não há dados disponíveis.');

        return tag('table', class: 'responsibleTable', children: 
        [
            tag('thead', children:
            [
                tag('tr', children: 
                [
                    ...array_map( function($col) use (&$colCount)
                    {
                        $colCount++;
                        $comps = [];
                        if (in_array($col, $this->columnsToHide) === false)
                            $comps[] = tag('th', children: [ text($col) ]);

                        if ($colCount === count($this->dataRows[0]))
                        {
                            if (isset($this->detailsButtonURL) && !isset($this->columnNameAsDetailsButton)) $comps[] = tag('th', class: 'w-5', children: [ text('Detalhes') ]);
                            if (isset($this->editButtonURL)) $comps[] = tag('th', class: 'w-5', children: [ text('Editar') ]);
                            if (isset($this->deleteButtonURL)) $comps[] = tag('th', class: 'w-5', children: [ text('Excluir') ]);
                            if (isset($this->selectButtonOnClick)) $comps[] = tag('th', class: 'w-5', children: [ text('Selecionar') ]);

                            if (count($this->customButtons) > 0)
                                foreach ($this->customButtons as $label => $link)
                                    $comps[] = tag('th', class: 'w-5', children: [ text($label) ]);
                        }

                        return $comps;

                    }, array_keys($this->dataRows[0]))
                ])
            ]),
            tag('tbody', children: 
            [
                ...array_map(function($row) use (&$colCount2)
                {
                    return tag('tr', children:
                    [
                        ...array_map( function($column, $value) use (&$colCount2, &$row)
                        {
                            $colCount2++;
                            $comps = [];
                            if (in_array($column, $this->columnsToHide) === false)
                            {
                                if (isset($this->columnNameAsDetailsButton) && $this->columnNameAsDetailsButton === $column)
                                    $comps[] = tag('td', ...['data-th' => $column], children:
                                    [
                                        tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->detailsButtonURL), children:
                                        [
                                            self::formatCellContent($value)
                                        ])
                                    ]);
                                else
                                    $comps[] = tag('td', ...['data-th' => $column], children: [ self::formatCellContent($value) ]);
                            }

                            if ($colCount2 === count($row))
                            {
                                if (isset($this->detailsButtonURL) && !isset($this->columnNameAsDetailsButton)) $comps[] = tag('td', ...[ 'data-th' => 'Detalhes' ], class: 'w-5', children: 
                                [ 
                                    tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->detailsButtonURL), children: [ text('Detalhes') ])
                                ]);
                                if (isset($this->editButtonURL)) $comps[] = tag('td', ...[ 'data-th' => 'Editar' ], class: 'w-5', children: 
                                [ 
                                    tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->editButtonURL), children: [ text('Editar') ])
                                ]);
                                if (isset($this->deleteButtonURL)) $comps[] = tag('td', ...[ 'data-th' => 'Excluir' ], class: 'w-5', children: 
                                [ 
                                    tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->deleteButtonURL), children: [ text('Excluir') ])
                                ]);
                                if (isset($this->selectButtonOnClick)) $comps[] = tag('td', ...[ 'data-th' => 'Selecionar' ], class: 'w-5', children: 
                                [
                                    tag('a', class: 'link text-lg', href: str_replace('{param}', $row[$this->rudButtonsFunctionParamName], $this->selectButtonOnClick), children: [ text('Selecionar') ])
                                ]);

                                if (count($this->customButtons) > 0)
                                    foreach ($this->customButtons as $label => $link)
                                        $comps[] = tag('td', ...[ 'data-th' => $label ], class: 'w-5', children: 
                                        [
                                            tag('a', class: 'link text-lg', href: self::applyCustomButtonsParameters($link, $this->customButtonsParameters, $row), children: [ text($label) ])
                                        ]);

                                $colCount2 = 0;
                            }

                            return $comps;
                        }, array_keys($row), $row)
                    ]);
                }, $this->dataRows)
            ])
        ]);
    }
}