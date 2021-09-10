<?php

class TokenNotValid extends Exception {

    public function __construct($message = "Token not valid!", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string {
        return "Message error: " . $this->message; 
    }
}

?>