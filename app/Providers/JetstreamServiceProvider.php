<?php

namespace App\Providers;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;
use App\Actions\Jetstream\UpdateTeamName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

// --- 1. YEH IMPORTS ZAROORI HAIN ---
use Illuminate\Support\Facades\Gate;
use App\Models\Team;
use App\Policies\TeamPolicy;
// ---

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Yeh function ab tamam roles (admin, editor, work-bees) ko configure karega
        $this->configurePermissions();

        // --- 2. YEH NAYI LINE AAPKA 403 ERROR FIX KAREGI ---
        // Yeh Jetstream ko batayegi ke hamari custom 'TeamPolicy' istemal karo
        Gate::policy(Team::class, TeamPolicy::class);
        // ---

        // --- 3. DUPLICATE ROLES YAHAN SE HATA DIYE GAYE HAIN ---
        // (admin aur editor roles yahan se hata diye gaye hain)

        Jetstream::createTeamsUsing(CreateTeam::class);
        Jetstream::updateTeamNamesUsing(UpdateTeamName::class);
        Jetstream::addTeamMembersUsing(AddTeamMember::class);
        Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
        Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
        Jetstream::deleteTeamsUsing(DeleteTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        // --- 4. TAMAM ROLES (CUSTOM BHI) SIRF YAHAN DEFINE HOTE HAIN ---
        Jetstream::role('admin', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('Administrator users can perform any action.');

        Jetstream::role('editor', 'Editor', [
            'read',
            'create',
            'update',
        ])->description('Editor users have the ability to read, create, and update.');

        Jetstream::role('work-bees', 'Work-Bees', [
            'read',
            'update-yellow',
        ])->description('Can edit project prioritization (Yellow).');

        Jetstream::role('developer', 'Developer', [
            'read',
            'update-red',
        ])->description('Can edit project costs (Red).');
    }
}
