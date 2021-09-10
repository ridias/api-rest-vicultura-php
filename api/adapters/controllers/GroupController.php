<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require_once __DIR__ . '/../../application/services/AuthenticationService.php';
require_once __DIR__ . '/../../application/services/GroupService.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../application/helpers/jwtHelper.php';
require_once __DIR__ . '/../../application/dtos/RequestDto.php';
require_once __DIR__ . '/../../application/dtos/UserTokenDetailsDto.php';

class GroupController extends BaseController {

    private GroupService $service;

    public function __construct(GroupService $groupService)
    {
        $this->service = $groupService;
    }

    public function getAllByUserId(Request $request, Response $response): Response {
        $token = isset($_COOKIE["vicultura_token"]) ? $_COOKIE["vicultura_token"] : "";

        if(strlen($token) == 0){
            $response->getBody()->write($this->getUnauthorizedPayload("Token expired!"));
            return $response;
        }

        $userTokenDetails = JwtHelper::decodeToken($token);
        $requestDto = new RequestDto(array(), $userTokenDetails);
        $responseDto = $this->service->getAllByUserId($requestDto);

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

    public function getGroupById(Request $request, Response $response, $args): Response {
        $data = $args;
        $token = isset($_COOKIE["vicultura_token"]) ? $_COOKIE["vicultura_token"] : "";

        if(strlen($token) == 0){
            $response->getBody()->write($this->getUnauthorizedPayload("Token expired!"));
            return $response;
        }

        $userTokenDetails = JwtHelper::decodeToken($token);
        $requestDto = new RequestDto($data, $userTokenDetails);
        $responseDto = $this->service->getGroupById($requestDto);

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

    public function update(Request $request, Response $response, $args): Response {
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

    public function delete(Request $request, Response $response, $args): Response {
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