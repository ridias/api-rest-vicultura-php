<?php

    require_once __DIR__.'/../../domain/entities/Material.php';
    require_once __DIR__.'/../../domain/validators/MaterialValidator.php';
    require_once __DIR__.'/../interfaces/MaterialRepository.php';
    require_once __DIR__.'/../helpers/jwtHelper.php';
    require_once __DIR__.'/../transformers/MaterialTransformer.php';
    require_once __DIR__ . '/../dtos/RequestPaginationDto.php';
    require_once __DIR__ . '/../dtos/ResponsePaginationDto.php';
    require_once __DIR__ . '/../dtos/ResponseDto.php';

    class MaterialService {

        private AuthenticationService $authenticationService;
        private MaterialRepository $materialRepository;
        private MaterialValidator $materialValidator;

        public function __construct(MaterialRepository $materialRepository, 
            AuthenticationService $authenticationService,
            MaterialValidator $materialValidator)
        {
            $this->materialRepository = $materialRepository;
            $this->materialValidator = $materialValidator;
            $this->authenticationService = $authenticationService;
        }

        public function getAll(RequestPaginationDto $request): ResponsePaginationDto {
            $response = new ResponsePaginationDto();
            $currentPage = $request->getCurrentPage();
            $limit = $request->getLimit();
            $userTokenDetails = $request->getTokenDetails();
            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            
            $idUser = $userTokenDetails->getId();

            if($idUser != 4)
                return $response->fail(new InvalidParameter("You don't have permission to access to this information", 401));
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired", 401));
            
            $total = $this->materialRepository->getTotal();
            if($limit <= 0) $limit = 20;
            if($currentPage <= 0) $currentPage = 1;
            $start = ($currentPage - 1) * $limit;

            $resultDtos = array();
            $resultEntities = $this->materialRepository->getAll($start, $limit);
            for($i = 0; $i < count($resultEntities); $i++){
                array_push($resultDtos, MaterialTransformer::transformToDto($resultEntities[$i])->toJSON());
            }

            $args = array(
                "currentPage" => $currentPage,
                "limit" => $limit,
                "total" => $total
            );

            return $response->ok($resultDtos, $args);
        }

        public function getById(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $id = array_key_exists("id", $data) ? $data["id"] : -1;
            $userTokenDetails = $request->getTokenDetails();
            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);

            $idUser = $userTokenDetails->getId();

            if($id <= 0)
                return $response->fail(new InvalidParameter("The id must be superior than 0", 400));
            if($idUser != 4)
                return $response->fail(new InvalidParameter("You don't have permission to access to this information", 401));
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired", 401));

            $resultDtos = array();
            $material = $this->materialRepository->getById($id);
            if($material->getId() > 0)
                array_push($resultDtos, MaterialTransformer::transformToDto($material)->toJSON());

            return $response->ok($resultDtos);
        }

        public function add(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $materialDto = new MaterialDto();
            $materialDto->setAllAvailableParameters($data);
            $materialDto->setDateCreated(new DateTime('NOW'));
            $material = MaterialTransformer::transformToEntity($materialDto);

            $userTokenDetails = $request->getTokenDetails();

            $this->materialValidator->setMaterial($material);
            if(!$this->materialValidator->isNameValid()) 
                return $response->fail(new InvalidParameter("The length of the name must be between 2 and 1024.", 400));
            if(!$this->materialValidator->isYearValid())
                return $response->fail(new InvalidParameter("The year must be positive.", 400));
            if(!$this->materialValidator->isImageValid())
                return $response->fail(new InvalidParameter("The length of the image must be less than 1024.", 400));
            if(!$this->materialValidator->isUrlImdbValid())
                return $response->fail(new InvalidParameter("The length of the url imdb must be less than 1024.", 400));

            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));

            $materialWithSameName = $this->materialRepository->getByName($material->getName());
            if(count($materialWithSameName) > 0){
                return $response->fail(new Duplicated("The material with name " . $material->getName() . " is already inserted in database!", 400));
            }

            $materialAdded = $this->materialRepository->add($material);
            return $response->ok(array(MaterialTransformer::transformToDto($materialAdded)->toJSON()));
        }

        public function update(RequestDto $request): ResponseDto {
            $response = new ResponseDto();
            $data = $request->getData();
            $userTokenDetails = $request->getTokenDetails();
            $idUser = $userTokenDetails->getId();

            $materialDto = new MaterialDto();
            $materialDto->setAllAvailableParameters($data);
            $material = MaterialTransformer::transformToEntity($materialDto);

            $this->materialValidator->setMaterial($material);
            if(!$this->materialValidator->isIdValid())
                return $response->fail(new InvalidParameter("The id is less or equal to 0.", 400));
            if(!$this->materialValidator->isNameValid()) 
                return $response->fail(new InvalidParameter("The length of the name must be between 2 and 1024.", 400));
            if(!$this->materialValidator->isYearValid())
                return $response->fail(new InvalidParameter("The year must be positive.", 400));
            if(!$this->materialValidator->isImageValid())
                return $response->fail(new InvalidParameter("The length of the image must be less than 1024.", 400));
            if(!$this->materialValidator->isUrlImdbValid())
                return $response->fail(new InvalidParameter("The length of the url imdb must be less than 1024.", 400));

            $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
            if($idUser != 4)
                return $response->fail(new InvalidParameter("You don't have permission to access to this information", 401));
            if(!$isTokenValid)
                return $response->fail(new TokenNotValid("The token is not valid or it's expired", 401));

            $materialWithSameName = $this->materialRepository->getByName($material->getName());

            if(count($materialWithSameName) > 0){
                if($materialWithSameName[0]->getId() != $material->getId()){
                    return $response->fail(new Duplicated("The material with name " . $material->getName() . " is already inserted in database!", 400));
                }
            }

            $this->materialRepository->update($material);
            return $response->ok(array($materialDto->toJSON()));
        }
    }

?>