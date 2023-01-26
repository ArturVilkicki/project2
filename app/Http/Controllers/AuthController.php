<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Carbon\Carbon;
use App\Services\AuthService;

class AuthController extends Controller
{
  protected $authService;

  public function __construct(AuthService $authService)
  {
    $this->authService = $authService;
  }

  public function register(Request $request){

    $user = $this->authService->register($request);

    $token = $user->createToken('authToken')->plainTextToken;

    return response()->json([
        'access_token' => $token,
        // 'token_type' => 'Bearer',
        'message' => 'Registration succesfull',
        'user_name' => $user->name,
        'user_email' => $user->email,
        'user_phone' => $user->phone,
    ]);
  }

  public function login(Request $request){

    $user = $this->authService->login($request);

    $token = $user->createToken('authToken')->plainTextToken;
    return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
    ]);
  }
}
