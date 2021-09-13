<?php

require_once __DIR__ . '/ResponseBaseDto.php';

class ResponseDto extends ResponseBaseDto {

    public bool $success;
    public int $totalCount;
    public array $items;
    public ?Exception $error;

    public function __construct(){
    
        
    }

    public function ok(array $data, array $args = null): ResponseDto {
        $response = new ResponseDto();
        $response->success = true;
        $response->totalCount = count($data);
        $response->items = $data;
        $response->error = NULL;
        return $response;
    }

    public function fail(Exception $ex): ResponseDto {
        $response = new ResponseDto();
        $response->success = false;
        $response->totalCount = 0;
        $response->items = array();
        $response->error = $ex;
        return $response;
    }
}