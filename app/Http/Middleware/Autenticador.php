<?php


namespace App\Http\Middleware;

use App\User;
use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class Autenticador
{
    public function handle(Request $request, Closure $next)
    {
        try {
            if (!$request->hasHeader('Authorization')) {
                throw new \Exception();
            }
            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);
            $dadosAutenticacao = JWT::decode($token, env('JWT_KEY'), ['HS256']); /* ['HS256'] -> UM DOS ALGORITMOS SUPORTADOS PELA BIBLIOTECA 'firebase/php-jwt' PARA DECODIFICAR O TOKEN*/

            $user =  User::where('email', $dadosAutenticacao->email)->first();

            if(is_null($user)){
                throw new \Exception();
            }
            return $next($request);
        } catch (\Exception $e){
            return response()->json('Não autorizado', 401);
    }
    }
}
