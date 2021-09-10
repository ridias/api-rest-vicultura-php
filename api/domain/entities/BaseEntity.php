<?php

    abstract class BaseEntity {

        private int $id;

        public function __construct()
        {

        }

        public function setId(int $id): void { $this->id = $id; }

        public function getId(): int { return $this->id; }

        public function toString(): string {
            return "Id: " . $this->id;
        }
    }
?>