<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required',
            'mobile' => 'required|numeric|digits:10|unique:users',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;

        return $this->sendResponse($success, 'User register successfully.');
    }

    /**
     * Login api
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            'mobile' => 'required|digits:10', // Adjust the digits based on your requirement
        ]);

        $user = User::where('mobile', $request->mobile)->first();

        if(!$user) {
            return response()->json(['error' => 'Mobile number not found.'], 404);
        }

        Auth::login($user);

        // Step 4: Generate API token
        $success['token'] =  $user->createToken('MyApp')->plainTextToken;
        $success['name'] =  $user->name;
        $success['id'] =  $user->id;

        // Step 5: Return success response
        return $this->sendResponse($success, 'User logged in successfully.');
    }

    // User Logout
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    // Get Logged-in User Info
    public function user(Request $request)
    {
        $success['user'] = User::all();
        return $this->sendResponse($success, 'User data get in successfully.');
    }
}
