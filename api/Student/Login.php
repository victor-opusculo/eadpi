<?php

namespace VictorOpusculo\Eadpi\Api\Student;

use VictorOpusculo\PComp\RouteHandler;

require_once __DIR__ . '/../../lib/Middlewares/JsonBodyParser.php';

final class Login extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\jsonParser';
    }

    protected function POST(): void
    {
        header('Content-Type:application/json');
        echo json_encode([ 'success' => $_POST['data']['email'] ?? 'n/d' ]);
        exit;
    }
}