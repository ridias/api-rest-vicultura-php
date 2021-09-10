<?php

    include_once __DIR__.'/BaseEntity.php';
    include_once __DIR__.'/Material.php';

    class Objective extends BaseEntity {

        private int $minProgress;
        private int $maxProgress;
        private int $currentProgress;
        private DateTime $dateCreated;
        private int $idGroup;
        private Material $material;

        public function __construct()
        {
            parent::__construct();
        }

        public function getMinProgress(): int { return $this->minProgress; }
        public function getMaxProgress(): int { return $this->maxProgress; }
        public function getCurrentProgress(): int { return $this->currentProgress; }
        public function getDateCreated(): DateTime { return $this->dateCreated; }
        public function getIdGroup(): int { return $this->idGroup; }
        public function getMaterial(): Material { return $this->material; }

        public function setMinProgress(int $value): void { $this->minProgress = $value; }
        public function setMaxProgress(int $value): void { $this->maxProgress = $value; }
        public function setCurrentProgress(int $value): void { $this->currentProgress = $value; }
        public function setDateCreated(DateTime $value): void { $this->dateCreated = $value; }
        public function setIdGroup(int $value): void { $this->idGroup = $value; }
        public function setMaterial(Material $value): void { $this->material = $value; }

        public function toString(): string {

            return $this->getId() . " - MinProgress: " . $this->minProgress . 
                " - MaxProgress: " . $this->maxProgress .
                " - CurrentProgress: " . $this->currentProgress .  
                " - DateCreated: " . $this->dateCreated->format('Y-m-d H:i:s') . 
                " - IdGroup: " . $this->idGroup . 
                " - Material: " . $this->material->toString();
        }

    }
?>