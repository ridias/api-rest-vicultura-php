<?php

require_once __DIR__ . '/../../infrastructure/repositories/GroupMysqlRepository.php';
require_once __DIR__ . '/../../infrastructure/repositories/ObjectiveMysqlRepository.php';
require_once __DIR__ . '/../../infrastructure/repositories/UserMysqlRepository.php';
require_once __DIR__ . '/../../infrastructure/repositories/SessionMysqlRepository.php';
require_once __DIR__ . '/../../domain/validators/ObjectiveValidator.php';
require_once __DIR__ . '/../../domain/validators/UserValidator.php';
require_once __DIR__ . '/../../application/services/ObjectiveService.php';
require_once __DIR__ . '/../../application/services/AuthenticationService.php';
require_once __DIR__ . '/../../adapters/controllers/ObjectiveController.php';

class CreatorObjectiveConfiguration {

    public static function getConfiguration(): ObjectiveController {
        $userRepository = new UserMysqlRepository();
        $sessionRepository = new SessionMysqlRepository();
        $objectiveRepository = new ObjectiveMysqlRepository();
        $groupRepository = new GroupMysqlRepository();
        $objectiveValidator = new ObjectiveValidator();
        $userValidator = new UserValidator();
        $authenticationService = new AuthenticationService($userRepository, $sessionRepository, $userValidator);
        $objectiveService = new ObjectiveService($objectiveRepository, $groupRepository, $authenticationService, $objectiveValidator);
        $controller = new ObjectiveController($objectiveService);
        return $controller;
    }
}

?>