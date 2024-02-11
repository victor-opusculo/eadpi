<?php
namespace VictorOpusculo\PComp;

interface IRenderable
{
	public function render() : void;
	public function renderToString() : string;
}