<?php

require_once __DIR__ . '/BaseDto.php';
require_once __DIR__ . '/MaterialDto.php';

class ObjectiveDto extends BaseDto {

    private int $minProgress;
    private int $maxProgress;
    private int $currentProgress;
    private DateTime $dateCreated;
    private MaterialDto $material;

    public function __construct()
    {
        
    }

    public function getMinProgress(): int { return $this->minProgress; }
    public function getMaxProgress(): int { return $this->maxProgress; }
    public function getCurrentProgress(): int { return $this->currentProgress; }
    public function getDateCreated(): DateTime { return $this->dateCreated; }
    public function getMaterial(): MaterialDto { return $this->material; }

    public function setMinProgress(int $value): void { $this->minProgress = $value; }
    public function setMaxProgress(int $value): void { $this->maxProgress = $value; }
    public function setCurrentProgress(int $value): void { $this->currentProgress = $value; }
    public function setDateCreated(DateTime $value): void { $this->dateCreated = $value; }
    public function setMaterial(MaterialDto $value): void { $this->material = $value; }

    public function setAllAvailableParameters(array $data): void
    {
        $materialDto = new MaterialDto();
        if(array_key_exists("material", $data)){
            $material = $data["material"];
            $materialDto->setId(array_key_exists("id", $material) ? $material["id"] : -1);
            $materialDto->setName(array_key_exists("name", $material) ? $material["name"] : "");
            $materialDto->setYear(array_key_exists("year", $material) ? $material["year"] : -1);
            $materialDto->setUrlImage(array_key_exists("urlImage", $material) ? $material["urlImage"] : "");
            $materialDto->setUrlDetails(array_key_exists("urlDetails", $material) ? $material["urlDetails"] : "");
        }

        $this->material = $materialDto;
        $id = array_key_exists("id", $data) ? $data["id"] : -1;
        $this->setId($id);
        $this->minProgress = array_key_exists("minProgress", $data) ? $data["minProgress"] : -1;
        $this->maxProgress = array_key_exists("maxProgress", $data) ? $data["maxProgress"] : -1;
        $this->currentProgress = array_key_exists("currentProgress", $data) ? $data["currentProgress"] : -1;
        $this->idGroup = array_key_exists("idGroup", $data) ? $data["idGroup"] : -1;
        $this->dateCreated = array_key_exists("minProgress", $data) ? 
            date_create_from_format('Y-m-d H:i:s', $data["dateCreated"]) : new DateTime();
    }

    public function toJSON(): string
    {
        return json_encode(array(
            "id" => $this->getId(),
            "minProgress" => $this->minProgress,
            "maxProgress" => $this->maxProgress,
            "currentProgress" => $this->currentProgress,
            "dateCreated" => $this->dateCreated->format("Y-m-d H:i:s"),
            "material" => array(
                "id" => $this->material->getId(),
                "name" => $this->material->getName(),
                "year" => $this->material->getYear(),
                "urlImage" => $this->material->getUrlImage(),
                "urlDetails" => $this->material->getUrlDetails()
            )
        ));
    }
}

?>