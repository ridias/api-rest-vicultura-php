<?php

require_once __DIR__ . '/ResponseBaseDto.php';

class ResponsePaginationDto extends ResponseBaseDto {

    public int $currentPage;
    public int $totalPages;
    public int $pageSize;
    public bool $hasPreviousPage;
    public bool $hasNextPage;
    public int $nextPageNumber;
    public int $previousPageNumber;

    public function __construct()
    {
        parent::__construct();
    }

    public function ok(array $data, array $args = null): ResponsePaginationDto
    {
        $response = new ResponsePaginationDto();
        $response->success = true;
        $response->totalCount = count($data);
        $response->items = $data;
        $response->error = NULL;

        if(array_key_exists("total", $args) && array_key_exists("limit", $args) && array_key_exists("currentPage", $args)){
            $total = $args["total"];
            $limit = $args["limit"];
            $currentPage = $args["currentPage"];

            $response->currentPage = $currentPage;
            $response->totalPages = ceil($total / $limit);
            $response->pageSize = $limit;
            $response->hasPreviousPage =  ($currentPage - 1) <= 0 ? false : true;
            $response->hasNextPage = ($currentPage + 1) <= $response->totalPages ? true : false;
            $response->nextPageNumber = ($currentPage + 1) <= $response->totalPages ? $currentPage + 1 : -1;
            $response->previousPageNumber = ($currentPage - 1) <= 0 ? -1 : $currentPage - 1;
        }

        return $response;
    }

    public function fail(Exception $ex): ResponsePaginationDto
    {
        $response = new ResponsePaginationDto();
        $response->success = false;
        $response->totalCount = 0;
        $response->items = array();
        $response->error = $ex;

        $response->currentPage = -1;
        $response->totalPages = -1;
        $response->pageSize = -1;
        $response->hasPreviousPage = false;
        $response->hasNextPage = false;
        $response->nextPageNumber = -1;
        $response->previousPageNumber = -1;
        return $response;
    }
}

?>