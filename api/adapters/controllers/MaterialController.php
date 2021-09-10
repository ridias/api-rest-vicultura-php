<?php

    use Psr\Http\Message\ResponseInterface as Response;
    use Psr\Http\Message\ServerRequestInterface as Request;

    require_once __DIR__ . '/../../application/services/MaterialService.php';
    require_once __DIR__ . '/BaseController.php';
    require_once __DIR__ . '/../../application/helpers/jwtHelper.php';
    require_once __DIR__ . '/../../application/dtos/RequestDto.php';
    require_once __DIR__ . '/../../application/dtos/RequestPaginationDto.php';
    require_once __DIR__ . '/../../application/dtos/UserTokenDetailsDto.php';

    class MaterialController extends BaseController {

        private MaterialService $service;

        public function __construct(MaterialService $service)
        {
            $this->service = $service;
        }

        public function getAll(Request $request, Response $response): Response {
            $token = isset($_COOKIE["vicultura_token"]) ? $_COOKIE["vicultura_token"] : "";

            if(strlen($token) == 0){
                $response->getBody()->write($this->getUnauthorizedPayload("Token expired!"));
                return $response;
            }

            $params = $request->getQueryParams();
            $limit = array_key_exists("limit", $params) ? $params["limit"] : 0;
            $currentPage = array_key_exists("currentPage", $params) ? $params["currentPage"] : 0;

            $userTokenDetails = JwtHelper::decodeToken($token);
            $requestDto = new RequestPaginationDto(array(), $userTokenDetails);
            $requestDto->setCurrentPage($currentPage);
            $requestDto->setLimit($limit);
            $responseDto = $this->service->getAll($requestDto);

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

        public function getById(Request $request, Response $response, array $args): Response {
            $token = isset($_COOKIE["vicultura_token"]) ? $_COOKIE["vicultura_token"] : "";
            $id = array_key_exists("id", $args) ? intval($args["id"]) : -1;
            $data = array("id" => $id);

            if(strlen($token) == 0){
                $response->getBody()->write($this->getUnauthorizedPayload("Token expired!"));
                return $response;
            }

            $userTokenDetails = JwtHelper::decodeToken($token);
            $requestDto = new RequestPaginationDto($data, $userTokenDetails);
            $responseDto = $this->service->getById($requestDto);

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

        public function update(Request $request, Response $response): Response {
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
    }
?>