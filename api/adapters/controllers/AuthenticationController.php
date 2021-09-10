<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

header("Content-Type: application/json");

require_once __DIR__ . '/../../application/services/AuthenticationService.php';
require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../../application/helpers/jwtHelper.php';
require_once __DIR__ . '/../../application/dtos/RequestDto.php';
require_once __DIR__ . '/../../application/dtos/UserTokenDetailsDto.php';

class AuthenticationController extends BaseController {
    
    private AuthenticationService $service; 

    public function __construct(AuthenticationService $service)
    {
        $this->service = $service;
    }

    public function register(Request $request, Response $response): Response {
        $json = $request->getBody();
        $data = json_decode($json, true);

        $requestDto = new RequestDto($data, new UserTokenDetailsDto());
        $responseDto = $this->service->register($requestDto);

        if(!$responseDto->success){
            $code = $responseDto->error->getCode();
            $message = $responseDto->error->getMessage();
            $payload = $this->getErrorPayloadByCode($code, $message);
            $response->getBody()->write($payload);
        }else{
            $response->getBody()->write($this->getOkPayload($responseDto));
        }

        $response = $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    public function login(Request $request, Response $response): Response {
        $json = $request->getBody();
        $data = json_decode($json, true);

        $requestDto = new RequestDto($data, new UserTokenDetailsDto());
        $responseDto = $this->service->login($requestDto);

        if(!$responseDto->success){
            $code = $responseDto->error->getCode();
            $message = $responseDto->error->getMessage();
            $payload = $this->getErrorPayloadByCode($code, $message);
            $response->getBody()->write($payload);
        }else{
            $response->getBody()->write($this->getOkPayload($responseDto));
            $token = $responseDto->items["token"];
            $response = $response->withHeader("Set-Cookie", urlencode("vicultura_token") . "=" . $token . "; Max-Age=86400 ; path=/; secure; httpOnly");
        }

        $response = $response->withHeader("Content-Type", "application/json");
        return $response;
    }

    public function isTokenValid($token): bool {
        if(!isset($token) || empty($token)) return false;

        try {
            $userTokenDetails = JwtHelper::decodeToken($token);
            return $this->service->isTokenValid($userTokenDetails);
        }catch(Exception $ex){
            return false;
        }
        
        return false;
    }

}

?>