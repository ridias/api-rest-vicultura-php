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
        $token = isset($_COOKIE["vicultura_token"]) ? $_COOKIE["vicultura_token"] : "";

        if(strlen($token) == 0){
            $response->getBody()->write($this->getUnauthorizedPayload("Token expired!"));
            return $response;
        }

        $params = $request->getQueryParams();
        $idGroup = array_key_exists("idGroup", $params) ? $params["idGroup"] : 0;
        $limit = array_key_exists("limit", $params) ? intval($params["limit"]) : 0;
        $currentPage = array_key_exists("currentPage", $params) ? intval($params["currentPage"]) : 0;

        $userTokenDetails = JwtHelper::decodeToken($token);
        $requestDto = new RequestPaginationDto(array("idGroup" => $idGroup), $userTokenDetails);
        $requestDto->setCurrentPage($currentPage);
        $requestDto->setLimit($limit);
        $responseDto = $this->service->getAllByIdGroup($requestDto);

        if(!$responseDto->success){
            $code = $responseDto->error->getCode();
            $message = $responseDto->error->getMessage();
            $payload = $this->getErrorPayloadByCode($code, $message);
            $response->getBody()->write($payload);
        }else{
            $response->getBody()->write($this->getOkPayload($responseDto));
        }

        $response = $response->withHeader("Content-Type", "application/json; charset=utf-8");
        return $response;
    }

    public function add(Request $request, Response $response): Response {
        $json = $request->getBody();
        $data = json_decode($json, true);
        $token = isset($_COOKIE["vicultura_token"]) ? $_COOKIE["vicultura_token"] : "";

        if(strlen($token) == 0){
            $response->getBody()->write($this->getUnauthorizedPayload("Token expired!"));
            return $response;
        }

        $userTokenDetails = JwtHelper::decodeToken($token);
        $requestDto = new RequestDto($data, $userTokenDetails);
        $responseDto = $this->service->add($requestDto);

        if(!$responseDto->success){
            $code = $responseDto->error->getCode();
            $message = $responseDto->error->getMessage();
            $payload = $this->getErrorPayloadByCode($code, $message);
            $response->getBody()->write($payload);
        }else{
            $response->getBody()->write($this->getOkPayload($responseDto));
        }
    
        $response = $response->withHeader("Content-Type", "application/json; charset=utf-8");
        return $response;
    }

    public function updateProgress(Request $request, Response $response): Response {
        $json = $request->getBody();
        $data = json_decode($json, true);
        $token = isset($_COOKIE["vicultura_token"]) ? $_COOKIE["vicultura_token"] : "";

        if(strlen($token) == 0){
            $response->getBody()->write($this->getUnauthorizedPayload("Token expired!"));
            return $response;
        }

        $userTokenDetails = JwtHelper::decodeToken($token);
        $requestDto = new RequestDto($data, $userTokenDetails);
        $responseDto = $this->service->update($requestDto);

        if(!$responseDto->success){
            $code = $responseDto->error->getCode();
            $message = $responseDto->error->getMessage();
            $payload = $this->getErrorPayloadByCode($code, $message);
            $response->getBody()->write($payload);
        }else{
            $response->getBody()->write($this->getOkPayload($responseDto));
        }

        $response = $response->withHeader("Content-Type", "application/json; charset=utf-8");
        return $response;
    }

    public function delete(Request $request, Response $response, array $args): Response {
        $data = $args;
        $token = isset($_COOKIE["vicultura_token"]) ? $_COOKIE["vicultura_token"] : "";

        if(strlen($token) == 0){
            $response->getBody()->write($this->getUnauthorizedPayload("Token expired!"));
            return $response;
        }

        $userTokenDetails = JwtHelper::decodeToken($token);
        $requestDto = new RequestDto($data, $userTokenDetails);
        $responseDto = $this->service->delete($requestDto);

        if(!$responseDto->success){
            $code = $responseDto->error->getCode();
            $message = $responseDto->error->getMessage();
            $payload = $this->getErrorPayloadByCode($code, $message);
            $response->getBody()->write($payload);
        }else{
            $response->getBody()->write($this->getOkPayload($responseDto));
        }

        $response = $response->withHeader("Content-Type", "application/json; charset=utf-8");
        return $response;
    }
}

?>