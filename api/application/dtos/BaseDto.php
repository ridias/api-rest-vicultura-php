<?php

abstract class BaseDto {

    private ?int $id;

    public function __construct()
    {
        
    }

    public function getId(): int { return $this->id; }
    public function setId(?int $val): void { $this->id = $val; }

    public abstract function toJSON(): string;
    public abstract function setAllAvailableParameters(array $data): void ;
}