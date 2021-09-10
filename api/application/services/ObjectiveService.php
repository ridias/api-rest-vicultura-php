<?php 

    include_once __DIR__.'/../../domain/entities/Objective.php';
    include_once __DIR__.'/../../domain/validators/ObjectiveValidator.php';
    include_once __DIR__.'/../interfaces/ObjectiveRepository.php';

    class ObjectiveService {

        private ObjectiveRepository $objectiveRepository;
        private ObjectiveValidator $objectiveValidator;
        private AuthenticationService $authenticationService;

        public function __construct(ObjectiveRepository $objectiveRepository, AuthenticationService $authenticationService, ObjectiveValidator $objectiveValidator)
        {   
            $this->objectiveRepository = $objectiveRepository;
            $this->objectiveValidator = $objectiveValidator;
            $this->authenticationService = $authenticationService;
        }

        public function getAllByIdGroup(int $idGroup, int $start, int $limit): array {
            if($idGroup <= 0) throw new Exception("The id of the group must be superior to 0.");
            if($start < 0 || $limit < 0) throw new Exception("The start and limit must be 0 or superior");
            return $this->objectiveRepository->getAllByIdGroup($idGroup, $start, $limit);
        }

        public function getTotalByIdGroup(int $idGroup): int {
            if($idGroup <= 0) throw new Exception("The id of the group must be superior to 0.");
            return $this->objectiveRepository->getTotalByIdGroup($idGroup);
        }

        public function add(Objective $objective): Objective {
            if(!isset($objective)) return new Exception("The objective to add is undefined!");
            
            $objective->setId(-1);

            $this->objectiveValidator->setObjective($objective);
            if(!$this->objectiveValidator->isMinProgressValid())
                throw new Exception("The minimum progress must be superior or equal to 0.");
            if(!$this->objectiveValidator->isMaxProgressValid())
                throw new Exception("The maximum progress must be superior or equal to 0.");
            if(!$this->objectiveValidator->isMinProgressLessThanMaxProgress())
                throw new Exception("The minimum progress must be less than the maximum progress.");
            if(!$this->objectiveValidator->isCurrentProgressValid())
                throw new Exception("The current progress must be between minimum and maximum progress.");
            if(!$this->objectiveValidator->isIdGroupValid())
                throw new Exception("The id group must be superior to 0.");
            if(!$this->objectiveValidator->isIdMaterialValid())
                throw new Exception("The id material must be superior to 0.");

            return $this->objectiveRepository->add($objective);
        }

        public function update(Objective $objective): void {
            if(!isset($objective)) throw new Exception("The objective to add is undefined!");

            $this->objectiveValidator->setObjective($objective);
            if(!$this->objectiveValidator->isIdValid())
                throw new Exception("The id of the objective must be superior to 0.");
            if(!$this->objectiveValidator->isMinProgressValid())
                throw new Exception("The minimum progress must be superior or equal to 0.");
            if(!$this->objectiveValidator->isMaxProgressValid())
                throw new Exception("The maximum progress must be superior or equal to 0.");
            if(!$this->objectiveValidator->isMinProgressLessThanMaxProgress())
                throw new Exception("The minimum progress must be less than the maximum progress.");

            $this->objectiveRepository->updateProgress($objective);
        }

        public function delete(int $id): void {
            if($id < 0) throw new Exception("The id to delete must be superior to 0.");
            $this->objectiveRepository->delete($id);
        } 

        
    }
?>