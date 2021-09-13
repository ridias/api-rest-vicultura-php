<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController extends BaseController {

    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function verifyPassword(Request $request, Response $response){

    }

    public function verifyEmail(Request $request, Response $response){

    }

    public function verifyUsername(Request $request, Response $response){

    }

    public function update(Request $request, Response $response){

    }

    public function updatePassword(Request $request, Response $response){

    }
}

?>