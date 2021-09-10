<?php

    include_once __DIR__.'/../../domain/entities/Material.php';

    interface MaterialRepository {

        public function getTotal(): int;
        public function getAll(int $start, int $limit): array;
        public function getById(int $id): Material;
        public function getByName(string $name): array;
        public function add(Material $material): Material;
        public function update(Material $material): void;
    }
?>