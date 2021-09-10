<?php

require_once __DIR__ . '/../interfaces/DataTransforObjectTransformer.php';
require_once __DIR__ . '/../dtos/GroupDto.php';
require_once __DIR__ . '/../../domain/entities/Group.php';

class GroupTransformer implements DataTransferObjectTransformer {

    public static function transformToDto($entity): GroupDto
    {
        $dto = new GroupDto();
        $dto->setId($entity->getId());
        $dto->setName($entity->getName());
        $dto->setDescription($entity->getDescription());
        $dto->setDateCreated($entity->getDateCreated());
        $dto->setDateEnd($entity->getDateEnd());
        return $dto;
    }

    public static function transformToEntity($dto): Group
    {
        $group = new Group();
        $group->setId($dto->getId());
        $group->setName($dto->getName());
        $group->setDescription($dto->getDescription());
        $group->setDateCreated($dto->getDateCreated());
        $group->setDateEnd($dto->getDateEnd());
        return $group;
    }
}