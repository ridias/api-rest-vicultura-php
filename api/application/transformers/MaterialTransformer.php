<?php 

require_once __DIR__ . '/../interfaces/DataTransforObjectTransformer.php';
require_once __DIR__ . '/../dtos/MaterialDto.php';
require_once __DIR__ . '/../../domain/entities/Material.php';

class MaterialTransformer implements DataTransferObjectTransformer {

    public static function transformToDto($entity): MaterialDto
    {
        $dto = new MaterialDto();
        $dto->setId($entity->getId());
        $dto->setName($entity->getName());
        $dto->setYear($entity->getYear());
        $dto->setDateCreated($entity->getDateCreated());
        $dto->setUrlImage($entity->getUrlImage());
        $dto->setUrlDetails($entity->getUrlDetails());
        return $dto;
    }

    public static function transformToEntity($dto): Material
    {
        $entity = new Material();
        $entity->setId($dto->getId());
        $entity->setName($dto->getName());
        $entity->setYear($dto->getYear());
        $entity->setDateCreated($dto->getDateCreated());
        $entity->setUrlImage($dto->getUrlImage());
        $entity->setUrlDetails($dto->getUrlDetails());
        return $entity;
    }
}

?>