<?php

require_once __DIR__ . '/BaseDto.php';

class GroupDto extends BaseDto {

    private string $name;
    private string $description;
    private DateTime $dateCreated;
    private DateTime $dateEnd;

    public function __construct()
    {
        
    }

    public function getName(): string { return $this->name; }
    public function getDescription(): string { return $this->description; }
    public function getDateCreated(): DateTime { return $this->dateCreated; }
    public function getDateEnd(): DateTime { return $this->dateEnd; }

    public function setName(string $value): void { $this->name = $value; }
    public function setDescription(string $value): void { $this->description = $value; }
    public function setDateCreated(DateTime $value): void { $this->dateCreated = $value; }
    public function setDateEnd(DateTime $value): void { $this->dateEnd = $value; }

    public function setAllAvailableParameters(array $data): void {
        $id = array_key_exists("id", $data) ? $data["id"] : -1;
        $this->setId($id);
        $this->name = array_key_exists("name", $data) ? $data["name"] : "";
        $this->description = array_key_exists("description", $data) ? $data["description"] : "";
        $this->dateCreated = array_key_exists("dateCreated", $data) ? 
            date_create_from_format('Y-m-d H:i:s', $data["dateCreated"]) : new DateTime();
        $this->dateEnd = array_key_exists("dateEnd", $data) ? 
            date_create_from_format('Y-m-d H:i:s', $data["dateEnd"]): null;
    }

    public function toJSON(): string {
        $dateEnd = !isset($this->dateEnd) ? NULL : $this->dateEnd->format("Y-m-d H:i:s");

        return json_encode(array(
            "id" => $this->getId(),
            "name" => $this->name,
            "description" => $this->description,
            "dateCreated" => $this->dateCreated->format("Y-m-d H:i:s"),
            "dateEnd" => $dateEnd
        ));
    }
}

?>