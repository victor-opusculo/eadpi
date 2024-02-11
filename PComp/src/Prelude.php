<?php
namespace VictorOpusculo\PComp\Prelude;

use VictorOpusculo\PComp\{Component, HtmlTag, HtmlSelfClosingTag, Text};

require_once "HtmlTag.php";
require_once "HtmlSelfClosingTag.php";
require_once "Component.php";
require_once "Text.php";

function tag(string $tagName, ...$properties) : Component
{
    return new HtmlTag($tagName, ...$properties);
}

function scTag(string $tagName, ...$properties) : Component
{
    return new HtmlSelfClosingTag($tagName, ...$properties);
}

function render(array $components) : void
{
    foreach ($components as $comp)
    {
        if ($comp instanceof Component)
            $comp->render();
        else if (is_array($comp))
            render($comp);
    }
}

function text(?string $string) : Component
{
    return new Text($string ?? '');
}

function rawText(?string $string) : Component
{
    return new Text($string ?? '', false);
}

function component(string $componentClassName, ...$properties) : Component
{
    return new $componentClassName(...$properties);
}