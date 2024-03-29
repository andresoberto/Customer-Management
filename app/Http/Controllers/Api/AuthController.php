<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\NewAccessToken;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends Controller
{


    public function register(Request $request){
       Log::channel('api')->info('New registration request from: '.$request->ip());
       $request->validate([
            "name"=>'required',
            'email'=> 'required|email|unique:users',
            'password'=>'required|confirmed'
       ]);

       $user= new User();
       $user->name=$request->name;
       $user->email=$request->email;
       $user->password=Hash::make($request->password);
       $user->save();
       
       log::channel('api')->debug('Server response', [
        'Status_code'=>Response::HTTP_CREATED,
        'Content'=>$user
       ]);

       return response($user, Response::HTTP_CREATED);
    }

    public function login(Request $request){
        Log::channel('api')->info('New login request from: '.$request->ip());
        $credentials=$request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);
        

        if(Auth::attempt($credentials)){
            $user=Auth::user();
            $token=$this->createPersonalToken($user)->plainTextToken;
            $cookie= cookie('cookie_token', $token, 60*24);

            log::channel('api')->debug('Server response', [
                'Status_code'=>Response::HTTP_OK,
                'Content'=>$token
            ]);

            return response(["token"=>$token], Response::HTTP_OK)->withoutCookie($cookie);
        }else{
            log::channel('api')->debug('Server response', [
                'Status_code'=>Response::HTTP_UNAUTHORIZED,
                'message'=>"Invalid Credentials"
            ]);

            return response(["message"=>"Invalid Credentials"], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function userProfile(Request $request){
        Log::channel('api')->info('New userProfile request from: '.$request->ip());
        log::channel('api')->debug('Server response', [
            'Status_code'=>Response::HTTP_OK,
            'userData'=>auth()->user()
        ]);
        return response()->json([
            "message"=>"User profile Ok",
            "userData"=>auth()->user()
        ], Response::HTTP_OK) ;
    }

    public function logout(Request $request){
        Log::channel('api')->info('logout from: '.$request->ip());
        $cookie=Cookie::forget('cookie_token');
        $token=$request->bearerToken();
        if (strpos($token, '|') !== false) {
            $token = substr($token, strpos($token, '|') + 1);
        }
        $token= DB::table('personal_access_tokens')->where('token', hash('sha1', $token))->delete();
        log::channel('api')->debug('Server response', [
            'Status_code'=>Response::HTTP_OK,
            "message"=>"session ended"
        ]);
        return response(["message"=>"session ended"], Response::HTTP_OK)->withoutCookie($cookie);
    }

    private function createPersonalToken(User $user)
    {
        $date = now()->format('Y-m-d H:i:s');
        $random = mt_rand(200, 500);
        $tokenString = $user->email . $date . $random;
        $abilities = ['*'];
        
        // Crear un registro de token personalizado en la base de datos
       $token =$user->tokens()->create([
            'name'=>"token",
            'token' => hash('sha1', $tokenString),
            'abilities' => $abilities,
            'expires_at' =>now()->addHours(1)
        ]);

        return new NewAccessToken($token, $token->getKey().'|'.$tokenString);
    }

}
