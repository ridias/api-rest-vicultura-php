<?php

    require_once __DIR__.'/BaseEntity.php';

    class User extends BaseEntity {

        private string $username;
        private string $email;
        private string $password;
        private DateTime $dateCreated;

        public function __construct()
        {
            
        }

        public function getUsername(): string { return $this->username; }
        public function getEmail(): string { return $this->email; }
        public function getPassword(): string { return $this->password; }
        public function getDateCreated(): DateTime { return $this->dateCreated; }

        public function setUsername(string $value): void { $this->username = $value; }
        public function setEmail(string $value): void { $this->email = $value; }
        public function setPassword(string $value): void { $this->password = $value; }
        public function setDateCreated(DateTime $value): void { $this->dateCreated = $value; }

        public function toString(): string {

            return $this->getId() . " - username: " . $this->username . 
                " - email: " . $this->email .
                " - password: " . $this->password .  
                " - DateCreated: " . $this->dateCreated->format('Y-m-d H:i:s');
        }
    }
?>