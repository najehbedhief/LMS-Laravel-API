<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\RoleRequest;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function approveRequest($requestId)
    {
        return DB::transaction(function () use ($requestId) {

            $roleRequest = RoleRequest::where('id', $requestId)
                ->where('status', 'pending')
                ->firstOrFail();

            $role = Role::where('name', $roleRequest->requested_role)->firstOrFail();

            $roleRequest->user->roles()->syncWithoutDetaching([$role->id]);

            $roleRequest->update(['status' => 'approved']);

            return response()->json([
                'message' => 'Role approved successfully',
            ]);
        });
    }

    public function rejectRequest($requestId)
    {
        return DB::transaction(function () use ($requestId) {

            $roleRequest = RoleRequest::where('id', $requestId)
                ->where('status', 'pending')
                ->firstOrFail();

            $roleRequest->update(['status' => 'rejected']);

            return response()->json([
                'message' => 'Role request rejected',
            ]);
        });
    }
}
