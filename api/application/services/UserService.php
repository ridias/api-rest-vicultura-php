<?php

class UserService {

    private UserRepository $userRepository;
    private AuthenticationService $authenticationService;
    private UserValidator $validator;

    public function __construct(UserRepository $userRepository, 
        AuthenticationService $authenticationService,
        UserValidator $userValidator)
    {
        $this->userRepository = $userRepository;
        $this->authenticationService = $authenticationService;
        $this->validator = $userValidator;
    }


    public function updateUsernameOrEmail(RequestDto $request): ResponseDto {
        $response = new ResponseDto();
        $data = $request->getData();
        $userTokenDetails = $request->getTokenDetails();

        $userDto = new UserDto();
        $userDto->setAllAvailableParameters($data);
        $user = UserTransformer::transformToEntity($userDto);

        $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
        if(!$isTokenValid)
            return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));

        $this->validator->setUser($user);
        if(!$this->validator->isUsernameValid()) 
            return $response->fail(new InvalidParameter("Username is not valid", 400));
        if(!$this->validator->isEmailValid())
            return $response->fail(new InvalidParameter("Email is not valid", 400));

        
        $userDuplicated = $this->isUserDuplicated($request);
        if($userDuplicated->totalCount > 0)
            return $response->fail(new Duplicated("The username is already in use."));

        $emailDuplicated = $this->isEmailDuplicated($request);
        if($emailDuplicated->totalCount > 0)
            return $response->fail(new Duplicated("The email is already in use."));

        $this->userRepository->update($user);
        return $response->ok(array($userDto->toJSON()));
    }

    public function updatePassword(RequestDto $request): ResponseDto {
        $response = new ResponseDto();
        $data = $request->getData();
        $userTokenDetails = $request->getTokenDetails();

        $password = array_key_exists("password", $data) ? $data["password"] : "";
        $idUser = $userTokenDetails->getId();

        $userDto = new UserDto();
        $userDto->setPassword($password);
        $userDto->setId($idUser);
        $user = UserTransformer::transformToEntity($userDto);

        $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);
        if(!$isTokenValid)
            return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));

        $this->validator->setUser($user);
        if(!$this->validator->isIdValid()) 
            return $response->fail(new InvalidParameter("User id is not valid, it must be superior than 0", 400));
        if(!$this->validator->isPasswordValid())
            return $response->fail(new InvalidParameter("Password is not valid", 400));

        $passwordHashed = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        $this->userRepository->updatePassword($passwordHashed, $idUser);
        return $response->ok(array(true));
    }

    public function verifyPassword(RequestDto $request): ResponseDto {
        $response = new ResponseDto();
        $data = $request->getData();
        $password = $data["password"];
        $userTokenDetails = $request->getTokenDetails();
        $username = $userTokenDetails->getUsername();

        $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);

        if(!$isTokenValid)
            return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));

        $user = $this->userRepository->getByUsername($username);

        if($user->getId() == -1){
            return $response->fail(new InvalidCredentials("The username does not exist!", 404));
        }else if(!password_verify($password, $user->getPassword())){
            return $response->fail(new InvalidCredentials("The password is incorrect!", 401));
        }

        return $response->ok(array(true));
    }

    public function isUserDuplicated(RequestDto $request): ResponseDto {
        $response = new ResponseDto();
        $data = $request->getData();
        $username = array_key_exists("username", $data) ? $data["username"] : "";
        $userTokenDetails = $request->getTokenDetails();
        $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);

        if(!$isTokenValid)
            return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));

        $user = $this->userRepository->getByUsername($username);
        if($user->getId() > 0)
            return $response->fail(new Duplicated("The username is already in use."));

        return $response->ok(array(true));
        
    }

    public function isEmailDuplicated(RequestDto $request): ResponseDto {
        $response = new ResponseDto();
        $data = $request->getData();
        $email = array_key_exists("email", $data) ? $data["email"] : "";
        $userTokenDetails = $request->getTokenDetails();
        $isTokenValid = $this->authenticationService->isTokenValid($userTokenDetails);

        if(!$isTokenValid)
            return $response->fail(new TokenNotValid("The token is not valid or it's expired.", 401));

        $user = $this->userRepository->getByEmail($email);
        if($user->getId() > 0)
            return $response->fail(new Duplicated("The username is already in use."));

        return $response->ok(array(true));
    }
}

?>