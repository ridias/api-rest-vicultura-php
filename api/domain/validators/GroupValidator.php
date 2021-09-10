<?php

    include_once __DIR__.'/../../application/interfaces/Validator.php';
    include_once __DIR__.'/../entities/Group.php';

    class GroupValidator implements Validator {

        private Group $group;
        
        public function __construct()
        {
            
        }

        public function setGroup(Group $group){
            $this->group = $group;
        }

        public function isIdValid(): bool
        {
            return $this->group->getId() > 0;
        }

        public function isNameValid(): bool {
            $name = $this->group->getName();
            if(isset($name) == false) return false;
            if(empty($name)) return false;
            $len = strlen($name);
            return 2 <= $len && $len <= 100; 
        }

        public function isDescriptionValid(): bool {
            $description = $this->group->getDescription();
            if(isset($description) == false) return false;
            if(empty($description)) return false;
            $len = strlen($description);
            return 2 <= $len && $len <= 150;
        }

        public function isUserValid(): bool {
            return $this->group->getIdUser() > 0;
        }
    }
?>