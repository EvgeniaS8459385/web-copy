<?php

namespace App\Policies;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\Module\ModulePartQuestion;
use App\Models\User;

class ModulePartQuestionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ModulePartQuestion $question): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ModulePartQuestion $question): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ModulePartQuestion $question): bool
    {
        return $user->isAdmin();
    }
}
