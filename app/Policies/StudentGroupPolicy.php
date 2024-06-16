<?php

namespace App\Policies;

use App\Models\StudentGroup\StudentGroup;
use App\Models\User;

class StudentGroupPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, StudentGroup $group): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, StudentGroup $group): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, StudentGroup $group): bool
    {
        return $user->isAdmin();
    }
}
