<?php

use Firebase\JWT\JWT;

require_once __DIR__ . '/../dtos/UserTokenDetailsDto.php';

class JwtHelper {  

    public static function generateToken(string $username, int $idUser): string {
        $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
        $issuedAt   = new DateTimeImmutable();
        $serverName = "vicultura.api";
        
        $data = [
            'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
            'iss'  => $serverName,                       // Issuer
            'nbf'  => $issuedAt->getTimestamp(),         // Not before
            'username' => $username,
            'idUser' => $idUser                      
        ];

        return JWT::encode(
            $data,
            $secretKey,
            'HS256'
        );
    } 

    public static function decodeToken(string $token): UserTokenDetailsDto {
        $userTokenDetails = new UserTokenDetailsDto();

        if(empty($token)){
            $userTokenDetails->setId(-1);
            $userTokenDetails->setUsername("");
        }else{
            $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
            $decoded = JWT::decode($token, $secretKey, ['HS256']);
            $userTokenDetails->setId($decoded->idUser);
            $userTokenDetails->setToken($token);
            $userTokenDetails->setUsername($decoded->username);
        }

        $userTokenDetails->setToken($token);
        return $userTokenDetails;
    }
}

?>