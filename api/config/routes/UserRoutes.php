<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../factories/CreatorAuthenticationController.php';

return function (App $app){

    $app->get('/users/verify/email', function (Request $request, Response $response, $args){

    });

    $app->get('/users/verify/username', function(Request $request, Response $response, $args){

    });

    $app->get('/users/verify/password', function(Request $request, Response $response, $args){

    });

    $app->post('/users/register', function (Request $request, Response $response, $args){
        $controller = CreatorAuthenticationController::getConfiguration();
        return $controller->register($request, $response);
    });

    $app->post('/users/login', function (Request $request, Response $response, $args){
        $controller = CreatorAuthenticationController::getConfiguration();
        return $controller->login($request, $response);
    });

    $app->put('/users', function (Request $request, Response $response, $args){

    });
};