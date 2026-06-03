<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\UserResource;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(User::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validatedData = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|max:50|unique:users',
            'password' => 'required|string|min:8',
        ]);

      $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
       ]);
       $token = $user->createToken('auth_token')->plainTextToken;
       return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return new UserResource(auth()->user());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
