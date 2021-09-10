<?php

    include_once __DIR__.'/../../domain/entities/Objective.php';

    interface ObjectiveRepository {

        public function getAllByIdGroup(int $idGroup, int $start, int $limit): array;
        public function add(Objective $objective): Objective;
        public function updateProgress(Objective $objective): void;
        public function delete(int $id): void;
        public function getTotalByIdGroup(int $idGroup): int;
    }
?>