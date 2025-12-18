<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\RoleRequestInterface;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct(RoleRequestInterface $roleRequestRepo)
    {
        $this->roleRequestRepo = $roleRequestRepo;
    }

    public function approveRequest($requestId)
    {
        return DB::transaction(function () use ($requestId) {

            $roleRequest = $this->roleRequestRepo->getPendingByIdOrFail($requestId);
            $this->roleRequestRepo->approveRequest($roleRequest);

            return response()->json([
                'message' => 'Role approved successfully',
            ]);
        });
    }

    public function rejectRequest($requestId)
    {
        return DB::transaction(function () use ($requestId) {

            $roleRequest = $this->roleRequestRepo->getPendingByIdOrFail($requestId);

            $this->roleRequestRepo->rejectRequest($roleRequest);

            return response()->json([
                'message' => 'Role request rejected',
            ]);
        });
    }
}
