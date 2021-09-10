<?php

class InvalidCredentials extends Exception {

    public function __construct($message = "Invalid credentials!", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string {
        return "Message error: " . $this->message; 
    }
}

?>