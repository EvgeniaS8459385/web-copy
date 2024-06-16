<?php

namespace App\Policies;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\Module\ModulePartQuestion;
use App\Models\Module\ModulePartQuestionAnswer;
use App\Models\User;

class ModulePartQuestionAnswerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ModulePartQuestionAnswer $answer): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, ModulePartQuestionAnswer $answer): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, ModulePartQuestionAnswer $answer): bool
    {
        return $user->isAdmin();
    }
}
