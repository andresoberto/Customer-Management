<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if ($request->bearerToken()) {
            
            if (Auth::onceUsingId($this->getUserIdFromToken($request->bearerToken()))) {
                
                return $next($request);
            }
        }

        Log::channel('api')->debug('Server response', [
            'Status_code'=>401,
            'error' => 'Unauthorized'
        ]);
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    protected function getUserIdFromToken($token)
    {
        if (strpos($token, '|') !== false) {
            $token = substr($token, strpos($token, '|') + 1);
        }
               
        $personalAccessToken = DB::table('personal_access_tokens')
        ->where('token', hash('sha1', $token))
        ->first();

       
        if ($personalAccessToken) {
            return $personalAccessToken->tokenable_id;
        }
        return null;
    }

}
