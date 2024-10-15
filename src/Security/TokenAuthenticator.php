<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TokenAuthenticator extends AbstractAuthenticator
{
    public function supports(Request $request): ?bool
    {
        // Verifica si el encabezado Authorization está presente en la solicitud
        return $request->headers->has('Authorization');
    }

    public function authenticate(Request $request): Passport
    {
        // Obtiene el token del encabezado Authorization
        $authorizationHeader = $request->headers->get('Authorization');
        
        if (!$authorizationHeader || !str_starts_with($authorizationHeader, 'Bearer ')) {
            throw new AuthenticationException('No token provided or invalid token format');
        }

        // Extrae el token (elimina la parte "Bearer ")
        $token = substr($authorizationHeader, 7);

        // Valida y procesa el token
        // Aquí puedes usar el token para validar al usuario con el UserBadge
        return new SelfValidatingPassport(new UserBadge($token));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // En caso de éxito, permite que la solicitud continúe normalmente
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // En caso de fallo de autenticación, devuelve un error 401
        return new Response('Authentication Failed: ' . $exception->getMessage(), Response::HTTP_UNAUTHORIZED);
    }
}
