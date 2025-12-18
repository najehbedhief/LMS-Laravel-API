<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\ChangePasswordRequest;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $studentRole = Role::where('name', 'Student')->firstOrFail();

        $user->roles()->attach($studentRole->id);

        return response()->json([
            'status' => '201',
            'Message' => 'User register successfully',
            'user' => $user->load('roles'),
        ]);
    }

    public function login(LoginUserRequest $request)
    {

        $user = User::where('email', $request->email)->first();

        if (! Auth::attempt($request->only('email', 'password'))) {
            return response()->json(
                [
                    'message' => 'invalid email or password',
                ],
                401
            );
        }

        return response()->json([
            'message' => 'Login Successful',
            'User' => $user->roles,
            'Token' => $user->createToken('api-token')->plainTextToken,
        ], 201);
    }

    public function changePassword(ChangePasswordRequest $request)
    {

        $user = auth()->user();
        // Check if old password matches
        if (! \Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Current password is incorrect.',
            ], 400);
        }
        // Update password
        $user->update([
            'password' => \Hash::make($request->new_password),
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Password changed successfully.',
        ], 200);
    }
}
