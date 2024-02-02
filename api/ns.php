<?php

namespace VictorOpusculo\Eadpi\Api;

return 
[
    '/student' => fn() =>
    [
        '/login' => Student\Login::class
    ]
];