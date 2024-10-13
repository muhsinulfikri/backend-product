<?php

namespace App\Http\Controllers\Authentication;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    //
    public function register(Request $request){
        $username = $request->input('username');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));
        $user = [
            'username' => $username,
            'email' => $email,
            'password' => $password
        ];
        $usernameExist = User::where('username', $username)->first();
        $emailExist = User::where('email', $email)->first();
        if (!$email || !$password){
            return response()->json(
                new UserResource('failed', 'Isilah email & password', null),
                400);
        }
        if ($usernameExist || $emailExist){
            return response()->json(
                new UserResource('failed', 'Username atau Email sudah terdaftar', null),
                400);
        }
        User::insert($user);
        return new UserResource('success', 'Register Berhasil', $user);
    }
    public function login(Request $request){
        $email = $request->input('email');
        $password = $request->input('password');
        if (!$email || !$password){
            return response()->json(
                new UserResource('failed', 'Isilah Email dan Password dengan benar', null),
                400);
        }
        // Cek user berdasarkan email
        $user = User::where('email', $email)->first();

        if ($user && Hash::check($password, $user->password)) {
            $token = Str::random(60);
            $user->forceFill([
                'api_token' => hash('sha256', $token),
            ])->save();
            return new UserResource('success', 'Login Berhasil', $user);
        } else {
            return response()->json(
                new UserResource('failed', 'Login Gagal, email atau password anda salah', null),
        404);
        }
    }
    public function logout(Request $request){
        $token = $request->bearerToken();
        $user = User::where('api_token', $token)->first();
        if (!$user || !$token) {
            return response()->json(new UserResource('failed', 'Logout gagal', $user), 400);
        }
        $user->forceFill([
            'api_token' => null,
        ])->save();
        return new UserResource('success', 'logout berhasil', null);
    }
}
