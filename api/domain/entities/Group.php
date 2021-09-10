<?php 
    require_once __DIR__.'/BaseEntity.php';

    class Group extends BaseEntity {

        private string $name;
        private string $description;
        private DateTime $dateCreated;
        private DateTime $dateEnd;
        private int $idUser;

        public function __construct()
        {
            parent::__construct();
        }

        public function getName(): string { return $this->name; }
        public function getDescription(): string { return $this->description; }
        public function getDateCreated(): DateTime { return $this->dateCreated; }
        public function getDateEnd(): DateTime { return $this->dateEnd; }
        public function getIdUser(): int { return $this->idUser; }

        public function setName(string $value): void { $this->name = $value; }
        public function setDescription(string $value): void { $this->description = $value; }
        public function setDateCreated(DateTime $value): void { $this->dateCreated = $value; }
        public function setDateEnd(DateTime $value): void { $this->dateEnd = $value; }
        public function setIdUser(int $value): void { $this->idUser = $value; }

        public function toString(): string {

            $dateEndFormat = $this->dateEnd == null ? " " : $this->dateEnd->format('Y-m-d H:i:s');

            return $this->getId() . " - Name: " . $this->name . 
                " - Description: " . $this->description . 
                " - DateCreated: " . $this->dateCreated->format('Y-m-d H:i:s') . 
                " - DateEnd: " . $dateEndFormat . 
                " - IdUser: " . $this->idUser;
        }
    }
?>