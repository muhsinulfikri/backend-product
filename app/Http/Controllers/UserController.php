<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //
    public function index()
    {
        //
        $user = User::all();
        return new UserResource(200, 'data list user', $user);
    }
    public function store(Request $request){
        $name = $request->input('name');
        $email = $request->input('email');
        $password = Hash::make($request->input('password'));

        $user = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];
        if (!$email || !$password){
            return response()->json(['message'=>'isilah email dan password'], 400);
        }
        User::insert($user);
        return new UserResource(201, 'user berhasil dibuat', $user);
    }
}
