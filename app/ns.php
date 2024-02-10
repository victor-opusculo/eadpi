<?php

namespace VictorOpusculo\Eadpi\App;

require_once "Home.php";
require_once "BaseLayout.php";

return 
[
    '/' => HomePage::class,
    '/infos' => fn() =>
    [
        '/courses' => fn() =>
        [
            '/' => \VictorOpusculo\Eadpi\App\Infos\Courses\Home::class,
            '/[id]' => \VictorOpusculo\Eadpi\App\Infos\Courses\CourseId::class
        ]
    ],
    '/students' => fn() =>
    [
        '/login' => Students\Login::class,
        '/register' => Students\Register::class,
        '/panel' => fn() =>
        [
            '/' => \VictorOpusculo\Eadpi\App\Students\Panel\PanelHome::class,
            '/subscription' => fn() => 
            [
                '/[id]' => \VictorOpusculo\Eadpi\App\Students\Panel\Subscription\SubsId::class
            ],
            '/module' => fn() =>
            [
                '/[id]' => \VictorOpusculo\Eadpi\App\Students\Panel\Module\ModId::class
            ],
            '/lesson' => fn() =>
            [
                '/[id]' => \VictorOpusculo\Eadpi\App\Students\Panel\Lesson\LessId::class
            ],
            '/test' => fn() => 
            [
                '/[id]' => fn() =>
                [
                    '/' => \VictorOpusculo\Eadpi\App\Students\Panel\Test\TestId\TestId::class,
                    '/[questId]' => \VictorOpusculo\Eadpi\App\Students\Panel\Test\TestId\QuestionId::class
                ]
            ],
            '__layout' => \VictorOpusculo\Eadpi\App\Students\Panel\PanelLayout::class
        ]
    ],
    '__layout' => BaseLayout::class,
    '__error' => BaseError::class
];