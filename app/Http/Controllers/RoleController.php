<?php

namespace App\Http\Controllers;

use App\Models\RoleRequest;
use App\Http\Requests\RoleRequestStoreRequest;

class RoleController extends Controller
{

public function requestRole(RoleRequestStoreRequest $request)
{
    $user = auth()->user();

    // Verify if there is already a pending request
    $exists = RoleRequest::where('user_id', $user->id)
        ->where('requested_role', $request->requested_role)
        ->where('status', 'pending')
        ->exists();

    if ($exists) {
        return response()->json([
            'message' => 'You already have a pending request'
        ], 409);
    }

    // Stocker la vidéo
    $videoPath = $request->file('intro_video_path')->store(
        'role_requests/videos',
        'public'
    );

    RoleRequest::create([
        'user_id' => $user->id,
        'requested_role' => $request->requested_role,
        'intro_video_path' => $videoPath,
        'status' => 'pending',
    ]);

    return response()->json([
        'message' => 'Role request submitted with introduction video',
        'user' => $user
    ], 201);
}

}
