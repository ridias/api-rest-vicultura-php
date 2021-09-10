<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../factories/CreatorAuthenticationController.php';

return function (App $app){

    $app->get('/users/tokenvalid', function (Request $request, Response $response, $args){
        $token = $_COOKIE["vicultura_token"];
        $controller = CreatorAuthenticationController::getConfiguration();
        $valid = $controller->isTokenValid($token);
        var_dump($token);
        var_dump($valid);
        echo "Token valid: " . $valid;
        return $response;
    });

    $app->post('/users/register', function (Request $request, Response $response, $args){
        $controller = CreatorAuthenticationController::getConfiguration();
        return $controller->register($request, $response);
    });

    $app->post('/users/login', function (Request $request, Response $response, $args){
        $controller = CreatorAuthenticationController::getConfiguration();
        return $controller->login($request, $response);
    });

    $app->put('/users/{id:[0-9]+}', function (Request $request, Response $response, $args){

    });
};