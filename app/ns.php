<?php

namespace VictorOpusculo\Eadpi\App;

require_once "Home.php";
require_once "BaseLayout.php";

return 
[
    '/' => HomePage::class,
    '__layout' => BaseLayout::class,
    '__error' => BaseError::class
];