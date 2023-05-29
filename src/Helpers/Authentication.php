<?php

namespace App\Helpers;

use LogicException;
use App\Models\Users;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use DateTimeImmutable;
use Exception;
use UnexpectedValueException;

class Authentication
{
    private static array $user;
    const LIFE_TIME_ONE_HOUR = 60;

    public static function login(string $email, #[\SensitiveParameter] string $password): array|bool
    {
        $user = (new Users())->findByEmail($email);
        if ($user == [])
            return false;

        if (!password_verify($password, $user['password']))
            return false;

        self::setCurrectUser($user);
        return ['token' => self::createToken($user['uuid']), 'user' => $user];
    }

    private static function createToken(string $uuid): string
    {
        $JWTKey = $_ENV['JWT_KEY'];
        $tokenLifeTime = $_ENV['JWT_TOKEN_LIFE_TIME'] ?? self::LIFE_TIME_ONE_HOUR;
        $issuedAt = new DateTimeImmutable();
        $expire = $issuedAt->modify("+{$tokenLifeTime} minutes")->getTimestamp();
        $appDomain = $_ENV['APP_DOMAIN'];

        $data = [
            'iat'  => $issuedAt->getTimestamp(),
            'iss'  => $appDomain,
            'nbf'  => $issuedAt->getTimestamp(),
            'exp'  => $expire,
            'user_uuid' => $uuid
        ];

        return JWT::encode($data, $JWTKey, 'HS512');
    }

    public static function getUserFromJWTToken(string $jwtToken): array|bool
    {
        try {
            $token = (array) JWT::decode($jwtToken, new Key($_ENV['JWT_KEY'], 'HS512'));
            $user = (new Users)->findByUuid($token['user_uuid']);
            return $user != [] ? $user : false;
        } catch (LogicException | UnexpectedValueException | Exception $e) {
            return false;
        }
    }

    public static function getCurrentUser()
    {
        return self::$user;
    }

    public static function setCurrectUser(array $user)
    {
        self::$user = $user;
    }

    public static function isUserAuthenticated(): bool
    {
        if (!$token = Request::getAuthorizationToken())
            return false;

        if (!$user = Authentication::getUserFromJWTToken($token))
            return false;

        Authentication::setCurrectUser($user);
        return true;
    }
}
