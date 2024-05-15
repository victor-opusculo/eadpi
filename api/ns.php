<?php

namespace VictorOpusculo\Eadpi\Api;

return 
[
    '/student' => fn() =>
    [
        '/login' => Student\Login::class,
        '/logout' => Student\Logout::class,
        '/register' => Student\Register::class,
        '/subscribe' => fn() =>
        [
            '/[courseId]' => Student\Subscribe\CourseId::class
        ],
        '/recover_password' => fn() =>
        [
            '/request_otp' => Student\RecoverPassword\RequestOtp::class,
            '/change_password' => Student\RecoverPassword\ChangePassword::class
        ],
        '/[id]' => Student\StudentId::class
    ],
    '/certificate' => fn() =>
    [
        '/auth' => Certificate\Auth::class
    ],
    '/administrator' => fn() =>
    [
        '/login' => Administrator\Login::class,
        '/logout' => Administrator\Logout::class,
        '/report' => fn() =>
        [
            '/course_subscriptions' => Administrator\Report\CourseSubscriptions::class
        ],
        '/media' => fn() =>
        [
            '/' => Administrator\Media\Home::class,
            '/[mediaId]' => Administrator\Media\MediaId::class
        ],
        '/partners' => fn() =>
        [
            '/' => Administrator\Partners\Home::class,
            '/[partnerId]' => Administrator\Partners\PartnerId::class
        ],
        '/certificates' => fn() =>
        [
            '/set_bg_image' => Administrator\Certificates\SetBgImage::class
        ],
        '/[id]' => Administrator\AdminId::class
    ]
];