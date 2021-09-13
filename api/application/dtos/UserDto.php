<?php

class UserDto extends BaseDto {

    private string $username;
    private string $email; 
    private string $password;
    
    public function __construct()
    {
        
    }

    public function getUsername(): string { return $this->username; }
    public function getEmail(): string { return $this->email; }
    public function getPassword(): string { return $this->password; }

    public function setUsername(string $val): void { $this->username = $val; }
    public function setEmail(string $val): void { $this->email = $val; }
    public function setPassword(string $val): void { $this->password = $val; }

    public function toJSON(): string
    {
        return json_encode(array(
            "id" => $this->getId(),
            "username" => $this->username,
            "email" => $this->email
        ));
    }

    public function setAllAvailableParameters(array $data): void
    {
        $id = array_key_exists("id", $data) ? $data["id"] : -1;
        $this->username = array_key_exists("username", $data) ? $data["username"] : "";
        $this->email = array_key_exists("email", $data) ? $data["email"] : "";
        $this->password = array_key_exists("password", $data) ? $data["password"] : "";
    }
}

?>