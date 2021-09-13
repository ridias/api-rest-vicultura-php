<?php

require_once __DIR__ . '/../../application/dtos/ResponseBaseDto.php';

abstract class BaseController {

    public function __construct()
    {
        
    }

    protected function getOkPayload(ResponseBaseDto $response): string {
        return $this->getResponsePayload("OK", "200", $response);
    }

    protected function getCreatedPayload(ResponseBaseDto $response): string {
        return $this->getResponsePayload("Created", "201", $response);
    }

    protected function getBadRequestPayload(string $message): string {
        return $this->getErrorPayload("Bad Request", "400", $message);
    }

    protected function getUnauthorizedPayload(string $message): string {
        return $this->getErrorPayload("Unauthorized", "401", $message);
    }

    protected function getForbiddenPayload(string $message): string {
        return $this->getErrorPayload("Forbidden", "403", $message);
    }

    protected function getNotFoundPayload(string $message): string {
        return $this->getErrorPayload("Not Found", "404", $message);
    }

    protected function getMethodNotAllowedPayload(string $message): string {
        return $this->getErrorPayload("Method Not Allowed", "405", $message);
    }

    protected function getInternalServerErrorPayload(string $message): string {
        return $this->getErrorPayload("Internal Server Error", "500", $message);
    }

    protected function getErrorPayloadByCode(int $code, string $message): string {
        if($code == 400){
            return $this->getBadRequestPayload($message);
        }else if($code == 401){
            return $this->getUnauthorizedPayload($message);
        }else if($code == 403){
            return $this->getForbiddenPayload($message);
        }else if($code == 404){
            return $this->getNotFoundPayload($message);
        }else if($code == 405){
            return $this->getMethodNotAllowedPayload($message);
        }else if($code == 500){
            return $this->getInternalServerErrorPayload($message);
        }

        return "";
    }

    private function getErrorPayload(string $status, string $code, string $message): string {
        return json_encode(array(
            "status" => $status,
            "code" => $code,
            "message" => $message
        ));
    }

    private function getResponsePayload(string $status, string $code, ResponseBaseDto $responseDto): string {
        return json_encode(array(
            "status" => $status,
            "code" => $code,
            "response" => $responseDto
        ));
    }
}

?>