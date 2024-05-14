<?php
namespace VictorOpusculo\Eadpi\Components\Data;

use VictorOpusculo\Eadpi\Lib\Helpers\QueryString;
use VictorOpusculo\PComp\Component;
use function VictorOpusculo\PComp\Prelude\{ tag, text };

class Paginator extends Component
{
    public function setUp()
    {
        $this->pageNum = isset($_GET['page_num']) && is_numeric($_GET['page_num']) ? (int)$_GET['page_num'] : 1;
    }

    protected int $totalItems;
    protected int $numResultsOnPage;
    protected int $pageNum;

    protected static function currQS() : string
    {
        return QueryString::getQueryStringForHtmlExcept('page_num');
    }

    protected static function addQSpagNum($pagNum) : string
    {
        return QueryString::formatNew('page_num', $pagNum, true);
    }

    protected function markup(): Component|array|null
    {
        if (ceil($this->totalItems / $this->numResultsOnPage) > 0)
            return tag('ul', class: 'pagination', children: 
            [
                $this->pageNum > 1 ? tag('li', class: 'prev', children: [ tag('a', href: "?" . self::currQS() . self::addQSpagNum($this->pageNum - 1), children: [ text('Anterior') ]) ] ) : null,
                $this->pageNum > 3 ?
                [
                    tag('li', class: 'start', children: [ tag('a', href: "?" . self::currQS() . self::addQSpagNum(1), children: [ text('1') ]) ]),
                    tag('li', class: 'dots', children: [ text('...') ])
                ] : null,

                ($this->pageNum - 2) > 0 ? tag('li', children: [ tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum-2), children: [ text($this->pageNum - 2) ]) ] ) : null,
                ($this->pageNum - 1) > 0 ? tag('li', children: [ tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum-1), children: [ text($this->pageNum - 1) ]) ] ) : null,

                tag('li', class: 'currentPageNum', children: [ tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum), children: [ text($this->pageNum) ]) ] ),

                ($this->pageNum + 1) < (ceil($this->totalItems / $this->numResultsOnPage) + 1) ? tag('li', children: [ tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum + 1), children: [ text($this->pageNum + 1) ]) ] ) : null,
                ($this->pageNum + 2) < (ceil($this->totalItems / $this->numResultsOnPage) + 1) ? tag('li', children: [ tag('a', href: '?' . self::currQS() . self::addQSpagNum($this->pageNum + 2), children: [ text($this->pageNum + 2) ]) ] ) : null,

                $this->pageNum < (ceil($this->totalItems / $this->numResultsOnPage) - 2) ?
                [
                    tag('li', class: 'dots', children: [ text('...') ]),
                    tag('li', class: 'end', children: [ tag('a', href: "?" . self::currQS() . self::addQSpagNum(ceil($this->totalItems / $this->numResultsOnPage)), children: [ text(ceil($this->totalItems / $this->numResultsOnPage)) ]) ])
                ] : null,
                $this->pageNum < (ceil($this->totalItems / $this->numResultsOnPage)) ? tag('li', class: 'next', children: [ tag('a', href: "?" . self::currQS() . self::addQSpagNum($this->pageNum + 1), children: [ text('Próxima') ]) ] ) : null
            ]);
        else
            return null;
    }
}