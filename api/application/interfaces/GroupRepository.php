<?php

    include_once __DIR__.'/../../domain/entities/Group.php';

    interface GroupRepository {

        public function getAllByUserId(int $idUser): array;
        public function getGroupById(int $id, int $idUser): Group;
        public function add(Group $group): Group;
        public function update(Group $group): void;
        public function delete(int $id): void;
        
    }
?>