<?php

require_once __DIR__ . '/../interfaces/DataTransforObjectTransformer.php';
require_once __DIR__ . '/../transformers/MaterialTransformer.php';
require_once __DIR__ . '/../dtos/ObjectiveDto.php';
require_once __DIR__ . '/../../domain/entities/Objective.php';

class ObjectiveTransformer implements DataTransferObjectTransformer {
    
    public static function transformToDto($entity): ObjectiveDto
    {
        $dto = new ObjectiveDto();
        $dto->setId($entity->getId());
        $dto->setMinProgress($entity->getMinProgress());
        $dto->setMaxProgress($entity->getMaxProgress());
        $dto->setCurrentProgress($entity->getCurrentProgress());
        $dto->setDateCreated($entity->getDateCreated());
        $dto->setMaterial(MaterialTransformer::transformToDto($entity->getMaterial()));
        return $dto;
    }

    public static function transformToEntity($dto): Objective
    {
        $entity = new Objective();
        $entity->setId($dto->getId());
        $entity->setMinProgress($dto->getMinProgress());
        $entity->setMaxProgress($dto->getMaxProgress());
        $entity->setCurrentProgress($dto->getCurrentProgress());
        $entity->setDateCreated($dto->getDateCreated());
        $entity->setMaterial(MaterialTransformer::transformToEntity($dto->getMaterial()));
        return $entity;
    }
}


?>