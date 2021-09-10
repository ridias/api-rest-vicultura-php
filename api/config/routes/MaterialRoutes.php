<?php

use Slim\App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

require __DIR__ . '/../factories/CreatorMaterialConfiguration.php';

return function (App $app){


    $app->get('/materials', function (Request $request, Response $response, $args){
        $controller = CreatorMaterialConfiguration::getConfiguration();
        return $controller->getAll($request, $response);
    });

    $app->post('/materials', function (Request $request, Response $response, $args){
        $controller = CreatorMaterialConfiguration::getConfiguration();
        return $controller->add($request, $response);
    });

    $app->put('/materials', function (Request $request, Response $response, $args){
        $controller = CreatorMaterialConfiguration::getConfiguration();
        return $controller->update($request, $response);
    });

    $app->get("/materials/{id:[0-9]+}", function (Request $request, Response $response, $args){
        $controller = CreatorMaterialConfiguration::getConfiguration();
        return $controller->getById($request, $response, $args);
    });
};