<?php

namespace App\Repositories\Interfaces;

use App\Models\RoleRequest;

interface RoleRequestInterface
{
    public function getPendingByIdOrFail($id);

    public function approveRequest(RoleRequest $roleRequest);

    public function rejectRequest(RoleRequest $roleRequest);
}
