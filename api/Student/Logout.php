<?php

namespace VictorOpusculo\Eadpi\Api\Student;

use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../lib/Middlewares/JsonBodyParser.php';

final class Logout extends RouteHandler
{
    public function __construct()
    {
    }

    protected function GET(): void
    {
        session_name('eadpi_student_user');
        session_start();
        LogEngine::writeLog("Log-off de aluno realizado.");
        session_unset();
        if (isset($_SESSION)) session_destroy();
        $this->json([ 'success' => 'Você saiu!' ]);
        exit;
    }
}