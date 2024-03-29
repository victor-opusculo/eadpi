<?php

namespace VictorOpusculo\Eadpi\App;

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
            '/edit_profile' => \VictorOpusculo\Eadpi\App\Students\Panel\EditProfile::class,
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
        ],
        '/recover_password' => \VictorOpusculo\Eadpi\App\Students\RecoverPassword::class
    ],
    '/certificate' => fn() =>
    [
        '/auth' => \VictorOpusculo\Eadpi\App\Certificate\Auth::class
    ],
    '/admin' => fn() =>
    [
        '/' => \VictorOpusculo\Eadpi\App\Admin\Login::class,
        '/login' => \VictorOpusculo\Eadpi\App\Admin\Login::class,
        '/panel' => fn() =>
        [
            '/' => \VictorOpusculo\Eadpi\App\Admin\Panel\PanelHome::class,
            '/edit_profile' => \VictorOpusculo\Eadpi\App\Admin\Panel\EditProfile::class,
            '__layout' => \VictorOpusculo\Eadpi\App\Admin\Panel\PanelLayout::class
        ]
    ],
    '__layout' => BaseLayout::class,
    '__error' => BaseError::class
];