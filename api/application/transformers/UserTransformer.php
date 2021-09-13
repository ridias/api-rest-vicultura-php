<?php

class UserTransformer implements DataTransferObjectTransformer {

    public static function transformToDto($entity): UserDto
    {
        $dto = new UserDto();
        $dto->setId($entity->getId());
        $dto->setUsername($entity->getUsername());
        $dto->setEmail($entity->getEmail());
        return $dto;
    }

    public static function transformToEntity($dto): User
    {
        $entity = new User();
        $entity->setId($dto->getId());
        $entity->setUsername($dto->getUsername());
        $entity->setEmail($dto->getEmail());
        return $entity;
    }
}

?>