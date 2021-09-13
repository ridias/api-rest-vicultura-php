<?php

class Forbidden extends Exception {

    public function __construct($message = "Forbidden!", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string {
        return "Message error: " . $this->message; 
    }
}

?>