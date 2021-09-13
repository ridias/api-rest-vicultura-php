<?php

    require_once __DIR__.'/../../domain/entities/Group.php';
    require_once __DIR__.'/../../application/dtos/GroupDto.php';
    require_once __DIR__.'/../../domain/validators/GroupValidator.php';
    require_once __DIR__.'/../interfaces/GroupRepository.php';
    require_once __DIR__.'/../transformers/GroupTransformer.php';
    require_once __DIR__.'/../../application/dtos/ResponseDto.php';

    class GroupService {

        private GroupRepository $groupRepository;
        private GroupValidator $groupValidator;
        private AuthenticationService $authenticationService;

        public function __construct(GroupRepository $groupRepository, 
            AuthenticationService $authenticationService,
            GroupValidator $groupValidator)
        {
            $this->groupRepository = $groupRepository;
            $this->authenticationService = $authenticationService;
            $this->groupValidator = $groupValidator;
        }

        public function getAllByUserId(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $userTokenDetails = $request->getTokenDetails();
            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);

            $idUser = $userTokenDetails->getId();

            if($idUser <= 0) 
                return $response->fail(new InvalidParameter("The id user must be superior than 0", 400));
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));

            $resultDtos = array();
            $resultEntities = $this->groupRepository->getAllByUserId($idUser);
            for($i = 0; $i < count($resultEntities); $i++){
                array_push($resultDtos, GroupTransformer::transformToDto($resultEntities[$i])->toJSON());
            }

            return $response->ok($resultDtos);
        }

        public function getGroupById(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $id = $data["id"];

            $userTokenDetails = $request->getTokenDetails();
            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            $idUser = $userTokenDetails->getId();

            if($idUser <= 0 || $id <= 0) 
                return $response->fail(new InvalidParameter("The id user must be superior than 0", 400));
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));
            
            $group = $this->groupRepository->getGroupById($id, $idUser);
            $resultDtos = array();
            if($group->getId() > 0)
                array_push($resultDtos, GroupTransformer::transformToDto($group)->toJSON());

            return $response->ok($resultDtos); 
        }

        public function add(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $groupDto = new GroupDto();
            $groupDto->setAllAvailableParameters($data);
            $groupDto->setDateCreated(new DateTime('NOW'));
            $group = GroupTransformer::transformToEntity($groupDto);

            $userTokenDetails = $request->getTokenDetails();
            $group->setIdUser($userTokenDetails->getId());

            $this->groupValidator->setGroup($group);
            if(!$this->groupValidator->isNameValid()) 
                return $response->fail(new InvalidParameter("The name of the group is not valid", 400));
            if(!$this->groupValidator->isDescriptionValid()) 
                return $response->fail(new InvalidParameter("The length of the description must be between 2 and 150.", 400));
            if(!$this->groupValidator->isUserValid()) 
                return $response->fail(new InvalidParameter("The user id is less or equal to 0, it's not valid.", 400));

            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));
            
            $groupAdded = $this->groupRepository->add($group);
            return $response->ok(array(GroupTransformer::transformToDto($groupAdded)->toJSON()));
        }

        public function update(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $userTokenDetails = $request->getTokenDetails();

            $groupDto = new GroupDto();
            $groupDto->setAllAvailableParameters($data);
            $group = GroupTransformer::transformToEntity($groupDto);
            $group->setIdUser($userTokenDetails->getId());

            $this->groupValidator->setGroup($group);
            if(!$this->groupValidator->isIdValid())
                return $response->fail(new InvalidParameter("The id is less or equal to 0, it's not valid.", 400));
            if(!$this->groupValidator->isNameValid()) 
                return $response->fail(new InvalidParameter("The name of the group is not valid", 400));
            if(!$this->groupValidator->isDescriptionValid()) 
                return $response->fail(new InvalidParameter("The length of the description must be between 2 and 150.", 400));
            if(!$this->groupValidator->isUserValid()) 
                return $response->fail(new InvalidParameter("The user id is less or equal to 0, it's not valid.", 400));

            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            $groupFromDb = $this->groupRepository->getGroupById($group->getId(), $userTokenDetails->getId());

            if($groupFromDb->getIdUser() != $userTokenDetails->getId())
                return $response->fail(new TokenNotValid("Token was compromised!", 401));
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));

            $this->groupRepository->update($group);
            return $response->ok(array($groupDto->toJSON()));
        }

        public function delete(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $id = $data["id"];

            if($id <= 0) 
                return $response->fail(new InvalidParameter("The id is less or equal to 0, it's not valid.", 400));

            $userTokenDetails = $request->getTokenDetails();
            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            $groupFromDb = $this->groupRepository->getGroupById($id, $userTokenDetails->getId());

            if($groupFromDb->getIdUser() != $userTokenDetails->getId())
                return $response->fail(new TokenNotValid("Token was compromised!", 401));
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));

            $this->groupRepository->delete($id);
            return $response->ok(array($id));
        }
    }
?>