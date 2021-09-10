<?php


interface DataTransferObjectTransformer {

    public static function transformToEntity($dto);
    public static function transformToDto($entity);
    
}

?>