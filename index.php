<?php

use VictorOpusculo\Eadpi\Lib\Helpers\URLGenerator;
use VictorOpusculo\PComp\{HeadManager, StyleManager, ScriptManager, AppInitializer};
use function VictorOpusculo\PComp\Prelude\render;

require_once "vendor/autoload.php";

$app = new AppInitializer(require_once __DIR__ . '/app/ns.php');

?><!DOCTYPE HTML>
<html>
	<head>
		<!-- Desenvolvido por Victor Opusculo -->
		<script>
			if (window.localStorage.darkMode === '1')
				document.documentElement.classList.add("dark");
		</script>
		<!--<script src="./clientComponents/dist/index.js" type="module"></script>-->
		<meta charset="utf8"/>
		<meta content="width=device-width, initial-scale=1" name="viewport" />
		<meta name="description" content="Plataforma EAD da Escola do Parlamento de Itapevi">
		<meta name="keywords" content="">
  		<meta name="author" content="Victor Opusculo Oliveira Ventura de Almeida">
		<link rel="stylesheet" href="<?= URLGenerator::generateFileUrl('assets/twoutput.css') ?>" />
		<link rel="shortcut icon" type="image/x-icon" href="<?= URLGenerator::generateFileUrl("assets/favicon.ico") ?>" />
		<?= HeadManager::getHeadText() ?>
		<?= StyleManager::getStylesText() ?>
	</head>
	<body>
		<?php render($app->mainFrameComponents); ?>
	</body>
	<?= ScriptManager::getScriptText() ?>
</html>