<?php

    include_once __DIR__.'/../../application/interfaces/Validator.php';

    class MaterialValidator implements Validator {
        
        private Material $material;

        public function __construct()
        {
            $this->material = new Material();
        }

        public function setMaterial(Material $material){
            $this->material = $material;
        }

        public function isIdValid(): bool
        {
            return $this->material->getId() > 0;
        }

        public function isNameValid(): bool {
            $name = $this->material->getName();
            if(isset($name) == false) return false;
            if(empty($name)) return false;
            $len = strlen($name);
            return 2 <= $len && $len <= 1024; 
        }

        public function isYearValid(): bool {
            return $this->material->getYear() > 0;
        }

        public function isImageValid(): bool {
            $image = $this->material->getUrlImage();
            if(isset($image) == false) return false;
            if(empty($image)) return false;
            return strlen($image) <= 1024;
        }

        public function isUrlImdbValid(): bool {
            $urlImdb = $this->material->getUrlDetails();
            if(isset($urlImdb) == false) return false;
            if(empty($urlImdb)) return false;
            return strlen($urlImdb) <= 1024;
        }
    }
?>