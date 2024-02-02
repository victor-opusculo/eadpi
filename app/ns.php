<?php

namespace VictorOpusculo\Eadpi\App;

require_once "Home.php";
require_once "BaseLayout.php";

return 
[
    '/' => HomePage::class,
    '/students' => fn() =>
    [
        '/login' => Students\Login::class,
        '/panel' => fn() =>
        [
            '/' => \VictorOpusculo\Eadpi\App\Students\Panel\PanelHome::class,
            '__layout' => \VictorOpusculo\Eadpi\App\Students\Panel\PanelLayout::class
        ]
    ],
    '__layout' => BaseLayout::class,
    '__error' => BaseError::class
];