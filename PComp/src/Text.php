<?php
namespace VictorOpusculo\PComp;

require_once "Component.php";

class Text extends Component
{
    public function __construct(private string $string, private bool $escapeHtml = true)
    {
        parent::__construct();
    }

    protected function markup() : ?Component
    {
        return null;
    }

    public function render() : void
    {
        echo $this->escapeHtml ? htmlspecialchars($this->string, ENT_QUOTES, 'UTF-8') : $this->string;
    }
}