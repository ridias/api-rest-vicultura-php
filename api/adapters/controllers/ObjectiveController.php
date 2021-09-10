<?php 

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../../application/services/ObjectiveService.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../application/helpers/jwtHelper.php';
require_once __DIR__ . '/../../application/dtos/RequestDto.php';
require_once __DIR__ . '/../../application/dtos/RequestPaginationDto.php';
require_once __DIR__ . '/../../application/dtos/UserTokenDetailsDto.php';

class ObjectiveController extends BaseController {
    
    private ObjectiveService $service;

    public function __construct(ObjectiveService $service)
    {
        $this->service = $service;
    }

    public function getAllByIdGroup(Request $request, Response $response): Response {
        return $response;
    }

    public function add(Request $request, Response $response): Response {
        return $response;
    }

    public function updateProgress(Request $request, Response $response): Response {
        return $response;
    }

    public function delete(Request $request, Response $response): Response {
        return $response;
    }
}

?>