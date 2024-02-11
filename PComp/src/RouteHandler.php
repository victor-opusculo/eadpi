<?php

namespace VictorOpusculo\PComp;

abstract class RouteHandler
{
    protected array $middlewares = [];

    protected function GET() : void { $this->notImplemented(); }
    protected function POST() : void { $this->notImplemented(); }
    protected function PUT() : void { $this->notImplemented(); }
    protected function PATCH() : void { $this->notImplemented(); }
    protected function DELETE() : void { $this->notImplemented(); }

    private function notImplemented() : void
    {
        header($_SERVER["SERVER_PROTOCOL"] . " 501 Not Implemented", true, 501);
		header('Content-Type: text/plain', true, 501);
		echo 'Erro 501!';
		exit;
    }

    protected function json(object|array $data, int $statusCode = 200) : void
    {
        header($_SERVER["SERVER_PROTOCOL"] . " $statusCode ", true, $statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
    }

    public function run(?array $urlParams) : void
    {
        if ($urlParams)
            foreach ($urlParams as $key => $value) 
            {
                $this->$key = $value;
            }

        foreach ($this->middlewares as $mid)
        {
            if (is_callable($mid))
                $mid();
        }
        
        switch ($_SERVER['REQUEST_METHOD'])
        {
            case 'GET': $this->GET(); break;
            case 'POST': $this->POST(); break;
            case 'PUT': $this->PUT(); break;
            case 'PATCH': $this->PATCH(); break;
            case 'DELETE': $this->DELETE(); break;
            default: 
                header("HTTP/1.1 405 Method Not Allowed", true, 405);
                exit;
        }
    }
}