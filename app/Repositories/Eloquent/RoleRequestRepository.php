<?php
namespace App\Repositories\Eloquent;


use App\Models\Role;
use App\Models\RoleRequest;
use App\Repositories\Interfaces\RoleRequestInterface;

class RoleRequestRepository implements RoleRequestInterface
{
    public function getPendingByIdOrFail($requestId)
    {
        return RoleRequest::where('id', $requestId)
            ->where('status', 'pending')
            ->firstOrFail();
    }

   
    public function approveRequest(RoleRequest $roleRequest)
    {
        $role = Role::where('name', $roleRequest->requested_role)->firstOrFail();

        $roleRequest->user->roles()->syncWithoutDetaching([$role->id]);

        $roleRequest->update(['status' => 'approved']);

        return $roleRequest;
    }

    
    public function rejectRequest(RoleRequest $roleRequest)
    {
        $roleRequest->update(['status' => 'rejected']);
        return $roleRequest;
    }
}