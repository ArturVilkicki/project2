<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class AuthService
{
    public function register(Request $request)
    {
      $post_data = $request->validate([
          'name' => 'required|string',
          'email' => 'required|string|email|unique:users',
          'password' => 'required|min:8',
          'phone' => 'required',
          'terms_and_privacy_policy_agree' => 'required'
      ]);

      $user = User::create([
          'name' => $post_data['name'],
          'email' => $post_data['email'],
          'phone' => $post_data['phone'],
          'password' => Hash::make($post_data['password']),
      ]);


      return $user;
    }

    public function login(Request $request)
    {
      $request->validate([
           'email' => 'required',
           'password' => 'required',
      ]);

      $user = User::where('email', $request['email'])->first();

      if (empty($user)) {
        $user = User::where('phone', $request['email'])->first();
      }

      if (!$user || !Hash::check($request->password, $user->password)) {
           return response('Login invalid', 503);
           RateLimiter::tooManyAttempts(request()->ip(), 4);

           RateLimiter::hit($this->throttleKey(), 180);

      }
      

      return $user;
    }
}
