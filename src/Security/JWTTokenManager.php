// src/Security/JWTTokenManager.php

namespace App\Security;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use App\Entity\User;

class JWTTokenManager
{
    private $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    public function createToken(User $user): string
    {
        $payload = [
            'user_id' => $user->getId(),
            'email' => $user->getEmail(),
            'exp' => time() + 3600, // Token válido por 1 hora
        ];

        return JWT::encode($payload, $this->secretKey);
    }

    public function decodeToken(string $token): array
    {
        try {
            return (array) JWT::decode($token, $this->secretKey, ['HS256']);
        } catch (ExpiredException $e) {
            throw new \Exception('Token expirado');
        } catch (\Exception $e) {
            throw new \Exception('Token inválido');
        }
    }
}
