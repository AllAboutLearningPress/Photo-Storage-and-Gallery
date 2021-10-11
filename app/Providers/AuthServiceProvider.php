<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        'App\Models\Photo' => 'App\Policies\PhotoPolicy',
        'App\Models\Invitation' => 'App\Policies\InvitationPolicy',
        'App\Models\Role' => 'App\Policies\RolePolicy'

    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('photos.trash', [\App\Policies\PhotoPolicy::class, 'trash']);
        Gate::define('photos.download',  [\App\Policies\PhotoPolicy::class, 'download']);

        //gates for invitations
        Gate::define('invitations.index', [\App\Policies\InvitationPolicy::class, 'viewAny']);
        Gate::define('invitations.create', [\App\Policies\InvitationPolicy::class, 'create']);
        Gate::define('invitations.delete', [\App\Policies\InvitationPolicy::class, 'delete']);

        //gates for roles

    }
}
