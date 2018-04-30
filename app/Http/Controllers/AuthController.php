<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTFactory;
use JWTAuth;
use App\user;
use Input;
use DB;
use Validator;
use Response;



class AuthController extends Controller
{
    //
	  /**
     * Create a new AuthController instance.
     *
     * @return void
     */

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

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

   

    // // function to register users data.
    public function register(Request $request)
    {
        // validation of user form
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
            'name' => 'required',
            'password'=> 'required'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors());
        }
        User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => bcrypt($request->get('password')),
            'token' => $request->get('token'),
        ]);
        $user = User::first();
        $token = JWTAuth::fromUser($user);
        
        return Response::json(compact('token'));
    }

    // function to get users Data.
    public function userData(Request $request)
    {
		//retrieving all users data for show.
		$usersData = DB::table('users')->orderBy('id','desc')->paginate(5);

		return response()->json([
			'data' => $usersData
		]);
    }

    
    // function to register courses data.
    public function newCourse(Request $request)
    {
    	// course data.    	
    	$courseName = $request->Input('courseName');

    	$courseData = DB::table('courses')->insert(['course_name' => $courseName ]);

    		// checking entered data if done or not successfully.
	    	if($courseData == true)
		    	{
		    		return response()->json([
		    			'state' => '1',
		    			'message' => 'data entered successfully , Thank You',
		    		]);
		    	}
	    	else
		    	{
		    		return response()->json([
		    			'state' => '0',
		    			'message' => 'Sorry Something went wrong please check entered data',
		    		]);
		    	} 
    }

    // function to get courses Data.
    public function getCoursesData(Request $request)
    {
		//retrieving all courses data for show.
		$coursesData = DB::table('courses')->orderBy('id','desc')->paginate(5);

		return response()->json([
			'data' => $coursesData
		]);
    }

    //assigning accounts with courses.
    public function reservation(Request $request)
    {
    	//reserving courses according to userID.
    	$userId = $Request->Input('userId');
    	$courseId = $Request->Input('courseId');

    	// for making a reservation for courses.
    	$reserve = DB::table('reservation')->insert(['user_id' => $userId,'course_id' => $courseId]);

	    	// checking reservation.
	    	if($reserve == true)
	    	{
	    		return response()->json([
	    			'state' => '1',
	    			'message' => 'You Assigned , Thank You',
	    		]);
	    	}
		    else
	    	{
	    		return response()->json([
	    			'state' => '0',
	    			'message' => 'Sorry Something went wrong please check reservation',
	    		]);
	    	} 
    }
}
