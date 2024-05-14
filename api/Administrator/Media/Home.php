<?php
namespace VictorOpusculo\Eadpi\Api\Administrator\Media;

require_once __DIR__ . '/../../../lib/Middlewares/AdminLoginCheck.php';

use Exception;
use VictorOpusculo\Eadpi\Lib\Helpers\LogEngine;
use VictorOpusculo\Eadpi\Lib\Model\Database\Connection;
use VictorOpusculo\Eadpi\Lib\Model\Media\Media;
use VictorOpusculo\PComp\RouteHandler;

final class Home extends RouteHandler
{
    public function __construct()
    {
        $this->middlewares[] = '\VictorOpusculo\Eadpi\Lib\Middlewares\adminLoginCheck';
    }

    protected function GET(): void
    {
        $conn = Connection::get();
        try
        {
            $searchKeywords = $_GET['q'] ?? '';
            $pageNum = $_GET['page_num'] ?? 1;
            $orderBy = $_GET['order_by'] ?? '';
            $numResultsOnPage = $_GET['num_results_on_page'] ?? 20;

            $getter = new Media();
            $count = $getter->getCount($conn, $searchKeywords);
            $data = $getter->getMultiple($conn, $searchKeywords, $orderBy, (int)$pageNum, $numResultsOnPage);

            $this->json([ 'success' => '...', 'data' => [ 'dataRows' => $data, 'allCount' => $count ] ]);
            exit;
        }
        catch (Exception $e)
        {
            $this->json([ 'error' => $e->getMessage() ], 500);
            exit;
        }
    } 

    protected function POST(): void
    {
        $conn = Connection::get();
        try
        {
            $media = new Media();
            $media->fillPropertiesFromFormInput($_POST, $_FILES);
            $result = $media->save($conn);
            if ($result['newId'])
            {
                LogEngine::writeLog("Mídia criada! ID: $result[newId]");
                $this->json([ 'success' => 'Mídia criada com sucesso!', 'data' => [ 'newId' => $result['newId'] ] ]);
                exit;
            }
            else
                throw new Exception("Erro ao criar mídia!");
        }
        catch (Exception $e)
        {
            LogEngine::writeErrorLog($e->getMessage());
            $this->json([ 'error' => $e->getMessage() ], 500);
            exit;
        }
    }
}