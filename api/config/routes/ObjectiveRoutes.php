<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../factories/CreatorObjectiveConfiguration.php';

return function (App $app){

    $app->get('/objectives', function (Request $request, Response $response, $args){
        $controller = CreatorObjectiveConfiguration::getConfiguration();
        return $controller->getAllByIdGroup($request, $response); 
    });

    $app->post('/objectives', function (Request $request, Response $response, $args){
        $controller = CreatorObjectiveConfiguration::getConfiguration();
        return $controller->add($request, $response); 
    });

    $app->put('/objectives', function(Request $request, Response $response, $args){
        $controller = CreatorObjectiveConfiguration::getConfiguration();
        return $controller->updateProgress($request, $response); 
    });

    $app->delete('/objectives/{id:[0-9]+}', function(Request $request, Response $response, $args){
        $controller = CreatorObjectiveConfiguration::getConfiguration();
        return $controller->delete($request, $response, $args); 
    });
};