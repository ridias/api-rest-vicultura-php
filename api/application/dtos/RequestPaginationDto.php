<?php

require_once __DIR__ . '/RequestDto.php';

class RequestPaginationDto extends RequestDto {

    private int $currentPage;
    private int $limit;

    public function __construct($data, UserTokenDetailsDto $tokenDetails)
    {
        parent::__construct($data, $tokenDetails);
    }

    public function getCurrentPage(): int { return $this->currentPage; }
    public function getLimit(): int { return $this->limit; }

    public function setCurrentPage(int $val): void { $this->currentPage = $val; }
    public function setLimit(int $val): void { $this->limit = $val; }
}

?>