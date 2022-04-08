<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    /**
     * Store a new user.
     *
     * @param  Request  $request
     * @return Response
     */
    public function register(Request $request)
    {
        //validate incoming request 
        $data = $request->all();
        $this->validate($request, [
            'username'      => 'required|string|unique:users',
            'password'      => 'required|confirmed',
            'email'         => 'required|string|unique:users',
            'country_code'  => 'required',
            'phone_number'  => 'required|unique:users'
        ]);

        try 
        {
            
            $data['password'] = app('hash')->make($request->input('password'));
            $user = User::create($data); 
                return response()->json(['status'=>'success', 
                                            'data' => $user,
                                            'message'=>'User successfull created','code'=>200]);

        } 
        catch (\Exception $e) 
        { 
            return response()->json(['status'=>'failed','message'=>'User failed create !','code'=>400]);
        } 
    }
	
     /**
     * Get a JWT via given credentials.
     *
     * @param  Request  $request
     * @return Response
     */	 
    public function login(Request $request)
    {
          //validate incoming request 
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only(['email', 'password']);

        if (! $token = Auth::attempt($credentials)) {			
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
	
     /**
     * Get user details.
     *
     * @param  Request  $request
     * @return Response
     */	 	
    public function profile()
    {
        return response()->json(auth()->user());
    }
}