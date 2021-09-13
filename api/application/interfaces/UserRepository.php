<?php

    include_once __DIR__.'/../../domain/entities/User.php';

    interface UserRepository {

        public function getById(int $id): User;
        public function getByUsername(string $username): User;
        public function getByEmail(string $email): User;
        public function getByUsernameAndId(int $id, string $username): User;
        public function add(User $user): User;
        public function update(User $user): void;
        public function updatePassword(string $password, int $idUser): void;
    }
?>