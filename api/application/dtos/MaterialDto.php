<?php

require_once __DIR__ . '/BaseDto.php';

class MaterialDto extends BaseDto {

    private string $name;
    private int $year;
    private DateTime $dateCreated;
    private string $urlImage;
    private string $urlDetails;

    public function __construct()
    {
        
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

    public function setAllAvailableParameters(array $data): void
    {
        $id = array_key_exists("id", $data) ? $data["id"] : -1;
        $this->setId($id);
        $this->name = array_key_exists("name", $data) ? $data["name"] : "";
        $this->year = array_key_exists("year", $data) ? $data["year"] : "";
        $this->dateCreated = array_key_exists("dateCreated", $data) ? 
            date_create_from_format('Y-m-d H:i:s', $data["dateCreated"]) : new DateTime();
        $this->urlImage = array_key_exists("urlImage", $data) ? $data["urlImage"] : "";
        $this->urlDetails = array_key_exists("urlDetails", $data) ? $data["urlDetails"] : "";
    }

    public function toJSON(): string
    {
        return json_encode(array(
            "id" => $this->getId(),
            "name" => $this->name,
            "year" => $this->year,
            "dateCreated" => $this->dateCreated->format("Y-m-d H:i:s"),
            "urlImage" => $this->urlImage,
            "urlDetails" => $this->urlDetails
        ));
    }
}

?>