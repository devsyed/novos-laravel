<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Http\JsonResponse; 
use App\Helpers\NovosResponseFormatter;
use Laravel\Sanctum\HasApiTokens;

class AuthController extends Controller
{
    use HasApiTokens;

    /** 
     * Login - Authenticate User
     * @param Request $request string|string
     * @return {user, token}
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return NovosResponseFormatter::formatError(['message' => $validator->errors()->first()], 422);
        }
    
        if (Auth::attempt($validator->validated())) {
            $user = Auth::user();
            $token = $user->createToken('api_token')->plainTextToken;
    
            return NovosResponseFormatter::formatSuccess([
                'user' => $user,
                'token' => $token,
            ],200);
        }
        return NovosResponseFormatter::formatError(['message' => __('auth.failed'), 'code' => 422],422);
    }



}
