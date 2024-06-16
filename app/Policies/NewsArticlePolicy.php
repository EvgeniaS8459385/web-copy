<?php

namespace App\Policies;

use App\Models\News\Article;
use App\Models\User;

class NewsArticlePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Article $article): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Article $article): bool
    {
        return $user->isAdmin();
    }
}
