<?php

namespace App\Providers;

use App\Models\Module\Module;
use App\Models\Module\ModulePart;
use App\Models\News\Article;
use App\Models\StudentGroup\StudentGroup;
use App\Models\User;
use App\Policies\ModulePartPolicy;
use App\Policies\ModulePolicy;
use App\Policies\NewsArticlePolicy;
use App\Policies\StudentGroupPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Module::class, ModulePolicy::class);
        Gate::policy(ModulePart::class, ModulePartPolicy::class);
        Gate::policy(StudentGroup::class, StudentGroupPolicy::class);
        Gate::policy(Article::class, NewsArticlePolicy::class);
    }
}
