<?php

require_once __DIR__ . '/ResponseDto.php';

class ResponsePaginationDto extends ResponseDto {

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
    
    public function okResponsePagination(array $data, array $pagination): ResponsePaginationDto {
        $response = new ResponsePaginationDto();
        $response->success = true;
        $response->totalCount = count($data);
        $response->items = $data;
        $response->error = NULL;
        $response->currentPage = $pagination["currentPage"];
        $response->totalPages = $pagination["totalPages"];
        $response->pageSize = $pagination["pageSize"];
        $response->hasPreviousPage = $pagination["hasPreviousPage"];
        $response->hasNextPage = $pagination["hasNextPage"];
        $response->nextPageNumber = $pagination["nextPageNumber"];
        $response->previousPageNumber = $pagination["previousPageNumber"];
        return $response;
    }
}

?>