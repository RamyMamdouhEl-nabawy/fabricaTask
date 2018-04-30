<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Request;
use Illuminate\Http\Request;
use JWTFactory;
use JWTAuth;
use App\user;
use Input;
use DB;
use Validator;
use Response;

class UsersController extends Controller
{
    public function __construct()
	    {
	        $this->middleware('auth:api', ['except' => ['login' , 'NewCourse' , 'Reservation'] ]);
	    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    // function used to view user Information.
    public function viewMe()
    {
        return response()->json(auth()->user());    	
    }
}
