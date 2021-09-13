<?php

abstract class ResponseBaseDto {

    public bool $success;
    public int $totalCount;
    public array $items;
    public ?Exception $error;

    public function __construct()
    {
        
    }

    public abstract function ok(array $data, array $args = null): ResponseBaseDto;
    public abstract function fail(Exception $ex): ResponseBaseDto;
} 

?>