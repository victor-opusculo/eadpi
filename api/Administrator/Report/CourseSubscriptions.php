<?php
namespace VictorOpusculo\Eadpi\Api\Administrator\Report;

require_once __DIR__ . '/../../../lib/Middlewares/AdminLoginCheck.php';

use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Reports\CourseSubscriptions as ReportsCourseSubscriptions;
use VictorOpusculo\PComp\RouteHandler;

class CourseSubscriptions extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\adminLoginCheck';
    }

    protected function GET() : void
    {
        $conn = Connection::create();
        $report = new ReportsCourseSubscriptions();
        $report->fetchData($conn);
        $conn->close();

        $report->output();
    }
}