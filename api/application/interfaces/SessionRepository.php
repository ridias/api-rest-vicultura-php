<?php

require_once __DIR__ . '/../../domain/entities/Session.php';

interface SessionRepository {
    public function getToken(int $idUser): Session;
    public function add(Session $session): void;
}


?>