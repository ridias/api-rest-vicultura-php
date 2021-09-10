<?php

require_once __DIR__ . '/../../infrastructure/repositories/UserMysqlRepository.php';
require_once __DIR__ . '/../../infrastructure/repositories/SessionMysqlRepository.php';
require_once __DIR__ . '/../../infrastructure/repositories/GroupMysqlRepository.php';
require_once __DIR__ . '/../../domain/validators/UserValidator.php';
require_once __DIR__ . '/../../domain/validators/GroupValidator.php';
require_once __DIR__ . '/../../application/services/AuthenticationService.php';
require_once __DIR__ . '/../../application/services/GroupService.php';
require_once __DIR__ . '/../../adapters/controllers/GroupController.php';


class CreatorGroupConfiguration {

    public static function getConfiguration(): GroupController {
        $userRepository = new UserMysqlRepository();
        $sessionRepository = new SessionMysqlRepository();
        $groupRepository = new GroupMysqlRepository();
        $groupValidator = new GroupValidator();
        $userValidator = new UserValidator();
        $authenticationService = new AuthenticationService($userRepository, $sessionRepository, $userValidator);
        $groupService = new GroupService($groupRepository, $authenticationService, $groupValidator);
        $controller = new GroupController($groupService);
        return $controller;
    }
}

?>