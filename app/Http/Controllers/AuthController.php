<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use App\Traits\ApiResponser;


class AuthController extends Controller
{
    
    use ApiResponser;

    protected $jwt;


    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
       // $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function getUsuario () {
        
        $response = User::select( 'IDUsuario as code',
        'Nombre as label'
        )
        ->get();

        return $this->successResponse($response);
    }

    public function login(Request $request)
    {

        try {
            $user = User::where('Usuario',  $request->username)->where('Password', $request->password)->first();
            if(!$user) {
                return $this->errorResponse('Credenciales incorrectas', 401);
            }
        } catch (JWTException $e) {

            return $this->errorResponse('Token absent', 500);
        }
        $usuario=$this->getDataUser($request->username);
        $token = $this->jwt->claims(['user' => $usuario])->fromUser($user);
        $this->jwt->setToken($token);
        return $this->successResponse($token);

    }

    public function getDataUser($username){

        $response=User::where('Usuario',$username)->get();
        $arregl=[];
           foreach($response as $rp){
            $dt=[
               'id'=>$rp['IDUsuario'],
               'nombre'=>$rp['Nombre']
         ];
         array_push($arregl, $dt);
         }
         return $arregl;
    }

    public function me() {
        return $this->successResponse("hola");
    }

    public function logout(Request $request)
    {
        $token = $request->header("Authorization");
        try {
            $this->jwt->setToken($this->getBearerToken($token));
            $this->jwt->invalidate();
            return $this->successResponse("Cerro sesión de manera exitosa");
        } catch (JWTException $e) {
            return $this->errorResponse("Falló el cerrar sesión", 500);
        }
    }

    function getBearerToken($headers) {
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return $this->successResponse($token);
    }

}
