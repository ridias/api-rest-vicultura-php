<?php 

    require_once __DIR__.'/../../domain/entities/Objective.php';
    require_once __DIR__.'/../../application/dtos/ObjectiveDto.php';
    require_once __DIR__.'/../../domain/validators/ObjectiveValidator.php';
    require_once __DIR__.'/../interfaces/ObjectiveRepository.php';
    require_once __DIR__.'/../transformers/ObjectiveTransformer.php';
    require_once __DIR__.'/../../application/dtos/ResponseDto.php';
    require_once __DIR__.'/../../application/dtos/ResponsePaginationDto.php';

    class ObjectiveService {

        private ObjectiveRepository $objectiveRepository;
        private ObjectiveValidator $objectiveValidator;
        private AuthenticationService $authenticationService;
        private GroupRepository $groupRepository;

        public function __construct(
            ObjectiveRepository $objectiveRepository, 
            GroupRepository $groupRepository, 
            AuthenticationService $authenticationService, 
            ObjectiveValidator $objectiveValidator)
        {   
            $this->objectiveRepository = $objectiveRepository;
            $this->objectiveValidator = $objectiveValidator;
            $this->authenticationService = $authenticationService;
            $this->groupRepository = $groupRepository;
        }

        public function getAllByIdGroup(RequestPaginationDto $request): ResponsePaginationDto {
            $response = new ResponsePaginationDto();
            $data = $request->getData();
            $idGroup = array_key_exists("idGroup", $data) ? $data["idGroup"] : -1;
            $currentPage = $request->getCurrentPage();
            $limit = $request->getLimit();
            $userTokenDetails = $request->getTokenDetails();
            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            $idUser = $userTokenDetails->getId();


            if($idGroup <= 0)
                return $response->fail(new InvalidParameter("The id group must be superior than 0", 400));
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired", 401));

            $group = $this->groupRepository->getGroupById($idGroup, $idUser);
            if($group->getId() <= 0)
                return $response->fail(new Forbidden("You don't have permission to access to this group", 403));
            
            $total = $this->objectiveRepository->getTotalByIdGroup($idGroup);
            if($limit <= 0) $limit = 20;
            if($currentPage <= 0) $currentPage = 1;
            $start = ($currentPage - 1) * $limit;

            $resultDtos = array();
            $resultEntities = $this->objectiveRepository->getAllByIdGroup($idGroup, $start, $limit);
            for($i = 0; $i < count($resultEntities); $i++){
                array_push($resultDtos, ObjectiveTransformer::transformToDto($resultEntities[$i])->toJSON());
            }

            $args = array(
                "currentPage" => $currentPage,
                "limit" => $limit,
                "total" => $total
            );

            return $response->ok($resultDtos, $args);
        }

        public function add(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $userTokenDetails = $request->getTokenDetails();
            $idUser = $userTokenDetails->getId();

            $objectiveDto = new ObjectiveDto();
            $objectiveDto->setAllAvailableParameters($data);
            $objectiveDto->setDateCreated(new DateTime('NOW'));
            $objective = ObjectiveTransformer::transformToEntity($objectiveDto);
            $objective->setId(-1);

            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired", 401));

            $this->objectiveValidator->setObjective($objective);
            if(!$this->objectiveValidator->isMinProgressValid())
                return $response->fail(new InvalidParameter("The minimum progress must be superior or equal to 0.", 400));
            if(!$this->objectiveValidator->isMaxProgressValid())
                return $response->fail(new InvalidParameter("The maximum progress must be superior or equal to 0.", 400));
            if(!$this->objectiveValidator->isMinProgressLessThanMaxProgress())
                return $response->fail(new InvalidParameter("The minimum progress must be less than the maximum progress.", 400));
            if(!$this->objectiveValidator->isCurrentProgressValid())
                return $response->fail(new InvalidParameter("The current progress must be between minimum and maximum progress.", 400));
            if(!$this->objectiveValidator->isIdGroupValid())
                return $response->fail(new InvalidParameter("The id group must be superior to 0.", 400));
            if(!$this->objectiveValidator->isIdMaterialValid())
                return $response->fail(new InvalidParameter("The id material must be superior to 0.", 400));

            $group = $this->groupRepository->getGroupById($objective->getIdGroup(), $idUser);
            if($group->getId() <= 0)
                return $response->fail(new Forbidden("You don't have permission to access to this group", 403));

            $objectiveAdded = $this->objectiveRepository->add($objective);
            return $response->ok(array(ObjectiveTransformer::transformToDto($objectiveAdded)->toJSON()));
        }

        public function update(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $userTokenDetails = $request->getTokenDetails();
            $idUser = $userTokenDetails->getId();

            $objectiveDto = new ObjectiveDto();
            $objectiveDto->setAllAvailableParameters($data);
            $objective = ObjectiveTransformer::transformToEntity($objectiveDto);

            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired", 401));

            $this->objectiveValidator->setObjective($objective);
            if(!$this->objectiveValidator->isIdValid())
                return $response->fail(new InvalidParameter("The id of the objective must be superior to 0."));
            if(!$this->objectiveValidator->isMinProgressValid())
                return $response->fail(new InvalidParameter("The minimum progress must be superior or equal to 0."));
            if(!$this->objectiveValidator->isMaxProgressValid())
                return $response->fail(new InvalidParameter("The maximum progress must be superior or equal to 0."));
            if(!$this->objectiveValidator->isMinProgressLessThanMaxProgress())
                return $response->fail(new InvalidParameter("The minimum progress must be less than the maximum progress."));

            $itBelongs = $this->objectiveRepository->doesObjectiveBelongToIdGroupAndIdUser($objective->getId(), $idUser, $objective->getIdGroup());
            if(!$itBelongs)
                return $response->fail(new Forbidden("You don't have permission to update information from other objective users.", 403));

            $this->objectiveRepository->updateProgress($objective);
            return $response->ok(array($objectiveDto->toJSON()));
        }

        public function delete(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $id = $data["id"];

            if($id <= 0) 
                return $response->fail(new InvalidParameter("The id is less or equal to 0, it's not valid.", 400));

            $userTokenDetails = $request->getTokenDetails();
            $idUser = $userTokenDetails->getId();
            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired", 401));

            $itBelongs = $this->objectiveRepository->doesObjectiveBelongToIdUser($id, $idUser);
            if(!$itBelongs)
                return $response->fail(new Forbidden("You don't have permission to update information from other objective users.", 403));

            $this->objectiveRepository->delete($id);
            return $response->ok(array("id" => $id));
        } 
    }
?>