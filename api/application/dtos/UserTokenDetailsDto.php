<?php 

require_once __DIR__ . '/BaseDto.php';

class UserTokenDetailsDto extends BaseDto {

    private string $username; 
    private string $token;

    public function __construct()
    {
        parent::__construct();
    }

    public function getUsername(): string { return $this->username; }
    public function getToken(): string { return $this->token; }

    public function setUsername(string $val): void { $this->username = $val; }
    public function setToken(string $val): void { $this->token = $val; }

    public function setAllAvailableParameters(array $data): void
    {
        //nothing
    }

    public function toJSON(): string
    {
        return "";
    }
}

?>