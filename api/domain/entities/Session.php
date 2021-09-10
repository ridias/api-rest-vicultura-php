<?php

require_once __DIR__.'/BaseEntity.php';

class Session extends BaseEntity{
    private string $token;
    private DateTime $dateCreated;
    private DateTime $dateExpiration;
    private int $idUser;

    public function __construct()
    {   
        parent::__construct();
    }

    public function getToken(): string { return $this->token; }
    public function getDateCreated(): DateTime { return $this->dateCreated; }
    public function getDateExpiration(): DateTime { return $this->dateExpiration; }
    public function getIdUser(): int { return $this->idUser; }

    public function setToken(string $val): void { $this->token = $val; }
    public function setDateCreated(DateTime $val): void { $this->dateCreated = $val; }
    public function setDateExpiration(DateTime $val): void { $this->dateExpiration = $val; }
    public function setIdUser(int $val): void { $this->idUser = $val; }
}

?>