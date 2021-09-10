<?php

    include_once __DIR__.'/BaseEntity.php';

    class Material extends BaseEntity {

        private string $name;
        private int $year;
        private DateTime $dateCreated;
        private string $urlImage;
        private string $urlDetails;

        public function __construct()
        {
            parent::__construct();
        }

        public function getName(): string { return $this->name; }
        public function getYear(): int { return $this->year; }
        public function getDateCreated(): DateTime { return $this->dateCreated; }
        public function getUrlImage(): string { return $this->urlImage; }
        public function getUrlDetails(): string { return $this->urlDetails; }

        public function setName(string $value): void { $this->name = $value; }
        public function setYear(int $value): void { $this->year = $value; }
        public function setDateCreated(DateTime $value): void { $this->dateCreated = $value; }
        public function setUrlImage(string $value): void { $this->urlImage = $value; }
        public function setUrlDetails(string $value): void { $this->urlDetails = $value; }

        public function toString(): string {

            return $this->getId() . " - Name: " . $this->name . 
                " - Year: " . $this->year . 
                " - DateCreated: " . $this->dateCreated->format('Y-m-d H:i:s') . 
                " - UrlImage: " . $this->urlImage . 
                " - UrlDetails: " . $this->urlDetails;
        }
    }
?>