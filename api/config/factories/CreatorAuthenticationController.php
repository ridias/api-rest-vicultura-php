<?php

require_once __DIR__ . '/../../infrastructure/repositories/UserMysqlRepository.php';
require_once __DIR__ . '/../../infrastructure/repositories/SessionMysqlRepository.php';
require_once __DIR__ . '/../../domain/validators/UserValidator.php';
require_once __DIR__ . '/../../application/services/AuthenticationService.php';
require_once __DIR__ . '/../../adapters/controllers/AuthenticationController.php';

class CreatorAuthenticationController {

    public static function getConfiguration(): AuthenticationController {

        $userRepository = new UserMysqlRepository();
        $sessionRepository = new SessionMysqlRepository();
        $validator = new UserValidator();
        $service = new AuthenticationService($userRepository, $sessionRepository, $validator);
        $controller = new AuthenticationController($service);
        return $controller;
    }
}


?>