<?php

class RequestDto {

    private $data;
    private UserTokenDetailsDto $tokenDetails;

    public function __construct($data, UserTokenDetailsDto $tokenDetails)
    {
        $this->tokenDetails = $tokenDetails;
        $this->data = $data;
    }

    public function getData() { return $this->data; }
    public function getTokenDetails(): UserTokenDetailsDto { return $this->tokenDetails; }
}

?>  