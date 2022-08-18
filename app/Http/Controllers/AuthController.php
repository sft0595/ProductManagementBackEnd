<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);


        if (Auth::attempt($request->only(['email', 'password']), $remember = false)) {
            $user = Auth::user();
            $token = $user->createToken('admin')->accessToken;
            return response(['token' => $token], Response::HTTP_ACCEPTED);
        }
        return response(['error' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }


    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname' =>  'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required',
            'password_confirm' => 'required|same:password'
        ]);
        $userCreated = User::create(
            $request->only('firstname', 'lastname', 'email', 'phone', 'address') +
                ['password' => Hash::make($request->password)]
        );
        $userCreated->image()->create(['image_url' => 'avatar.jpg']);
        return response($userCreated, Response::HTTP_CREATED);
    }
}
