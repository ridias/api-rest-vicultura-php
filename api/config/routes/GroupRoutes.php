<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../factories/CreatorGroupConfiguration.php';

return function (App $app){

    $app->get('/groups', function (Request $request, Response $response, $args){
        $controller = CreatorGroupConfiguration::getConfiguration();
        return $controller->getAllByUserId($request, $response);
    });

    $app->get('/groups/{id:[0-9]+}', function (Request $request, Response $response, $args){
        $controller = CreatorGroupConfiguration::getConfiguration();
        return $controller->getGroupById($request, $response, $args);
    });

    $app->post('/groups', function (Request $request, Response $response, $args){
        $controller = CreatorGroupConfiguration::getConfiguration();
        return $controller->add($request, $response);
    });

    $app->put('/groups/{id:[0-9]+}', function (Request $request, Response $response, $args){
        $controller = CreatorGroupConfiguration::getConfiguration();
        return $controller->update($request, $response, $args);
    });

    $app->delete('/groups/{id:[0-9]+}', function (Request $request, Response $response, $args){
        $controller = CreatorGroupConfiguration::getConfiguration();
        return $controller->delete($request, $response, $args);
    });
};