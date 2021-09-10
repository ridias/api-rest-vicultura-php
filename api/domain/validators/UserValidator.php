<?php

    include_once __DIR__.'/../../application/interfaces/Validator.php';
    include_once __DIR__.'/../entities/User.php';

    class UserValidator implements Validator {

        private User $user;

        public function __construct()
        {
            
        }

        public function setUser(User $user): void {
            $this->user = $user;
        }

        public function isIdValid(): bool
        {
            return $this->user->getId() > 0;
        }

        public function isUsernameValid(): bool {
            $username = $this->user->getUsername();
            if(isset($username) == false) return false;
            $pattern = "/^[A-Za-z0-9]{3,100}$/";
            return preg_match($pattern, $username);
        }

        public function isEmailValid(): bool {
            $email = $this->user->getEmail();
            if(isset($email) == false) return false;
            $pattern = "/^[\w\-\.]+@([\w\-]+\.)+[\w\-]{2,4}$/";
            return preg_match($pattern, $email);
        }

        public function isPasswordValid(): bool {
            $password = $this->user->getPassword();
            if(isset($password) == false) return false;
            $pattern = "/^(?=.*[A-Z_])(?=.*[a-z])(?=.*[0-9])(?=.*\W)[A-Za-z0-9_\W]{8,1024}$/";
            return preg_match($pattern, $password);
        }
    }
?>