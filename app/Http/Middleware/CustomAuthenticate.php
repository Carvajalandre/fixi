<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class CustomAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Rutas públicas que no requieren token
            if ($request->is(
                'api/login'
            )) {
                return $next($request);
            }

            // Obtener token Bearer
            $token = $request->bearerToken() ?? $request->cookie('access_token');

            if (!$token) {
                return $this->errorResponse('Token no proporcionado', 401);
            }

            // Validar token
            $user = JWTAuth::parseToken()->authenticate();

            if (!$user) {
                return $this->errorResponse('Usuario no encontrado', 401);
            }

            // Opcional: guardar user en request
            $request->attributes->add(['auth_user' => $user]);

        } catch (TokenExpiredException $e) {
            return $this->errorResponse('El token ha expirado', 401);
        } catch (TokenInvalidException $e) {
            return $this->errorResponse('Token inválido', 401);
        } catch (JWTException $e) {
            return $this->errorResponse('Error al procesar token', 401);
        }

        return $next($request);
    }

    private function errorResponse(string $message, int $status): Response
    {
        return response()->json([
            'error' => true,
            'message' => $message,
            'status' => $status
        ], $status);
    }
}
