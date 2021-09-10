<?php

require_once __DIR__ . '/../exceptions/InvalidCredentials.php';
require_once __DIR__ . '/../exceptions/InvalidParameter.php';
require_once __DIR__.'/../../domain/entities/User.php';
require_once __DIR__.'/../../domain/validators/UserValidator.php';
require_once __DIR__.'/../interfaces/UserRepository.php';
require_once __DIR__.'/../interfaces/SessionRepository.php';
require_once __DIR__.'/../helpers/jwtHelper.php';



class AuthenticationService {

    private SessionRepository $sessionRepository;
    private UserRepository $userRepository;
    private UserValidator $validator;
    

    public function __construct(UserRepository $userRepository, 
        SessionRepository $sessionRepository, 
        UserValidator $validator)
    {
        $this->sessionRepository = $sessionRepository;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    public function register(RequestDto $request): ResponseDto {
        $response = new ResponseDto();
        $data = $request->getData();
        
        $user = new User();
        $user->setUsername($data["username"]);
        $user->setEmail($data["email"]);
        $user->setPassword($data["password"]);
        $user->setDateCreated(new DateTime('NOW'));

        $this->validator->setUser($user);
        if(!$this->validator->isUsernameValid()) 
            return $response->fail(new InvalidParameter("Username is not valid", 400));
        if(!$this->validator->isEmailValid())
            return $response->fail(new InvalidParameter("Email is not valid", 400));
        if(!$this->validator->isPasswordValid())
            return $response->fail(new InvalidParameter("Password is not valid", 400));

        $userDuplicated = $this->userRepository->getByUsername($user->getUsername());
        if($userDuplicated->getId() > 0)
            return $response->fail(new InvalidParameter("Username is already in use", 400));
        
        $emailDuplicated = $this->userRepository->getByEmail($user->getEmail());
        if($emailDuplicated->getId() > 0)
            return $response->fail(new InvalidParameter("The email is already in use.", 400));

        $passwordHashed = password_hash($user->getPassword(), PASSWORD_BCRYPT);
        $user->setPassword($passwordHashed);

        $this->userRepository->add($user);
        return $response->ok(array());
    }

    public function login(RequestDto $request): ResponseDto {
        $response = new ResponseDto();
        $data = $request->getData();
        $username = $data["username"];
        $password = $data["password"];

        $user = $this->userRepository->getByUsername($username);
        
        if($user->getId() == -1){
            return $response->fail(new InvalidCredentials("The username does not exist!", 404));
        }else if(!password_verify($password, $user->getPassword())){
            return $response->fail(new InvalidCredentials("The password is incorrect!", 401));
        }

        $token = JwtHelper::generateToken($username, $user->getId());
        $dateExpiration = new DateTime('NOW');
        $dateExpiration->add(new DateInterval("P1D"));

        $session = new Session();
        $session->setToken($token);
        $session->setDateCreated(new DateTime('NOW'));
        $session->setDateExpiration($dateExpiration);
        $session->setIdUser($user->getId());

        $this->sessionRepository->add($session);

        return $response->ok(array("token" => $token));
    }

    public function isTokenValid(UserTokenDetailsDto $userTokenDetails): bool {
        $username = $userTokenDetails->getUsername();
        $idUser = $userTokenDetails->getId();
        $token = $userTokenDetails->getToken();
        
        if(!isset($token) || empty($token)){
            return false;
        }

        $user = $this->userRepository->getByUsernameAndId($idUser, $username);
        if($user->getId() == -1){
            return false;
        }else{
            $session = $this->sessionRepository->getToken($idUser);
            if($session->getToken() != $token) 
                return false;

            $now = new DateTime('NOW');
            if($session->getDateExpiration() < $now){
                return false;
            }
        }

        return true;
    }

    
}
?>