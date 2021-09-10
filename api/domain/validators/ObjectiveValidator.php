<?php

    include_once __DIR__.'/../../application/interfaces/Validator.php';
    include_once __DIR__.'/../entities/Objective.php';

    class ObjectiveValidator implements Validator {

        private Objective $objective;

        public function __construct()
        {
            
        }

        public function setObjective(Objective $objective): void {
            $this->objective = $objective;
        }

        public function isIdValid(): bool
        {
            return $this->objective->getId() > 0;
        }

        public function isMinProgressValid(): bool {
            return $this->objective->getMinProgress() >= 0;
        }

        public function isMaxProgressValid(): bool {
            return $this->objective->getMaxProgress() > 0;
        }

        public function isCurrentProgressValid(): bool {
            $currentProgress = $this->objective->getCurrentProgress();
            return $this->objective->getMinProgress() <= $currentProgress && $currentProgress <= $this->objective->getMaxProgress();
        }

        public function isMinProgressLessThanMaxProgress(): bool {
            return $this->objective->getMaxProgress() > $this->objective->getMinProgress();
        }

        public function isIdGroupValid(): bool {
            return $this->objective->getIdGroup() > 0;
        }

        public function isIdMaterialValid(): bool {
            return $this->objective->getMaterial()->getId() > 0;
        }
    } 

?>