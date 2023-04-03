<?php

namespace App\Http\Controllers;

//use App\User;
//use Validator;
//use Exception;
//use GuzzleHttp\Client;

use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

//use Illuminate\Support\Facades\Auth;
//use Laravel\Passport\Client as OClient;
//use Carbon\Carbon;

class AuthController extends Controller
{


    /*public function login(Request $request)
    {
        try{

            $credentials = $request->only('email', 'password');

            if (Auth::attempt($credentials)) {
                // Authentication passed...
                 $user = Auth::user();
                 $token = $user->createToken('grupogarflo_pass')->accessToken;

                return response()->json($token);
            }

        } catch (Exception  $e) {

            if ($e->getCode() === 400) {
                return response()->json('Invalid Request. Please enter a username or a password.', $e->getMessage());
            } else if ($e->getCode() === 401) {
                return response()->json('Your credentials are incorrect. Please try again', $e->getMessage());
            }

            return response()->json([
                'code'=>$e->getCode(),
                'message'=>'Something went wrong on the server.',
                'detail'=>$e->getMessage()
            ],
            $e->getCode());
        }
    }*/

    public function login(Request $request) {

        $validationRules = [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];


        $validator =  Validator::make($request->all(), $validationRules);

        try {
            if ($validator->fails()) {
                $this->throwValidationException($request, $validator);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $login = $request->only(['email', 'password']);

        if (Auth::attempt($login)) {
            try {
                $user = User::find(Auth::id());

                $profile = [];
                $profile['access_token'] = $user->createToken('authToken')->accessToken;
                //$profile['id'] = $user->id;
                $profile['name'] = $user->name;
                $profile['email'] = $user->email;



                //$response = ['data' => $profile];
                return response()->json($profile, 200);
            } catch (\Exception $ex) {
                $response = ['error' => $ex->getMessage(), 'message' => 'Unauthorized'];
                return response()->json($response, 401);
            }
        } else {
            $response = ['message' => 'Invalid credentials'];
            return response()->json($response, 401);
        }
    }

    public function logout()
    {
        auth()->user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response()->json('Logged out successfully', 200);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    /*
    //funcionamiento anterior
    // Registro de usuario

    public function signUp(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }


    //Inicio de sesión y creación de token

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        $credentials = request(['email', 'password']);

        if (!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);

        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ]);
    }


    //Cierre de sesión (anular el token)

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }


    // Obtener el objeto User como json

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
    */
}
