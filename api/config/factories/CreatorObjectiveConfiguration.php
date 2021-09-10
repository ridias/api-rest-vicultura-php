<?php


class CreatorObjectiveConfiguration {

    public static function getConfiguration(): ObjectiveController {
        $userRepository = new UserMysqlRepository();
        $sessionRepository = new SessionMysqlRepository();
        $objectiveRepository = new ObjectiveMysqlRepository();
        $objectiveValidator = new ObjectiveValidator();
        $userValidator = new UserValidator();
        $authenticationService = new AuthenticationService($userRepository, $sessionRepository, $userValidator);
        $objectiveService = new ObjectiveService($objectiveRepository, $authenticationService, $objectiveValidator);
        $controller = new ObjectiveController($objectiveService);
        return $controller;
    }
}

?>