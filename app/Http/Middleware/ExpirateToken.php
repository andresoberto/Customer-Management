<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class ExpirateToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        if ($request->bearerToken()) {
            
            if (Auth::onceUsingId($this->tokenExpired($request->bearerToken()))) {
                
                return $next($request);
            }
        }

        Log::channel('api')->debug('Server response', [
            'Status_code'=>401,
            'error' => 'The token has expired, please login'
        ]);
        return response()->json(['error' => 'The token has expired, please login'], 401);
    }

    protected function tokenExpired($token)
    {
        if (strpos($token, '|') !== false) {
            $token = substr($token, strpos($token, '|') + 1);
        }
               
        $accessToken = DB::table('personal_access_tokens')
        ->where('token', hash('sha1', $token))
        ->first();

       
        if ($accessToken && $accessToken->expires_at > now() ) {
            return true;
        }else{
            return false;
        }
        
    }
}
