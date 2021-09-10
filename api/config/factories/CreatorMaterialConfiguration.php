<?php

require_once __DIR__ . '/../../infrastructure/repositories/MaterialMysqlRepository.php';
require_once __DIR__ . '/../../infrastructure/repositories/UserMysqlRepository.php';
require_once __DIR__ . '/../../infrastructure/repositories/SessionMysqlRepository.php';
require_once __DIR__ . '/../../domain/validators/MaterialValidator.php';
require_once __DIR__ . '/../../application/services/MaterialService.php';
require_once __DIR__ . '/../../adapters/controllers/MaterialController.php';


class CreatorMaterialConfiguration {

    public static function getConfiguration(): MaterialController {
        $repository = new MaterialMysqlRepository();
        $validator = new MaterialValidator();
        $validatorUser = new UserValidator();
        $userRepository = new UserMysqlRepository();
        $sessionRepository = new SessionMysqlRepository();
        $authentication = new AuthenticationService($userRepository, $sessionRepository, $validatorUser);
        $service = new MaterialService($repository, $authentication, $validator);
        $controller = new MaterialController($service);
        return $controller;
    } 
}

?>