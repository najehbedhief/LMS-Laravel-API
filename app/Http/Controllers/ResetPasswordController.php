<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use App\Http\Requests\ResetPasswordRequest;

class ResetPasswordController extends Controller
{
    // Send reset password link
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return response()->json([
                'status' => 200,
                'message' => __($status),
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => __($status),
        ], 500);
    }

    // Reset the password
    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'status' => 200,
                'message' => 'Password has been reset successfully.',
            ]);
        }

        return response()->json([
            'status' => 500,
            'message' => __($status),
        ], 500);
    }
}
