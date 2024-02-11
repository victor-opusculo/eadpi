<?php

namespace VictorOpusculo\Eadpi\Lib\Middlewares;

use VictorOpusculo\Eadpi\Lib\Helpers\UserTypes;

function adminLoginCheck()
{
    session_name('eadpi_admin_user');
    session_start();

    if (($_SESSION['user_type'] ?? '') !== UserTypes::administrator)
    {
        session_unset();
        if (isset($_SESSION)) session_destroy();

        header('Content-Type: application/json', true, 401);
        echo json_encode([ 'error' => 'Você não está logado como administrador!' ]);
        exit;
    }
}