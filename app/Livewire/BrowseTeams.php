<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')]
class BrowseTeams extends Component
{
    public $teams;
    public $myTeamIds = [];

    public function mount()
    {
        $this->loadTeams();
    }

    /**
     * Tamam teams aur user ki teams ko load karein
     * (UPDATED LOGIC)
     */
    public function loadTeams()
    {
        $user = auth()->user();
        $this->teams = Team::where('personal_team', false)->get();

        // --- YEH RAHA AAPKA FIX ---

        // 1. Woh teams jinhein user ne join kiya hai (Member)
        $memberTeamIds = $user->teams()->pluck('teams.id');

        // 2. Woh teams jo Admin ne banayi hain (Owner)
        $ownedTeamIds = Team::where('user_id', $user->id)
                            ->where('personal_team', false) // Personal team ko chhor kar
                            ->pluck('id');

        // Donon lists ko milayein aur duplicates hatayein
        $this->myTeamIds = $memberTeamIds->merge($ownedTeamIds)->unique()->toArray();
        // --- FIX KHATAM ---
    }

    /**
     * Team join karne ka function (UPDATED)
     */
    public function joinTeam($teamId)
    {
        $user = auth()->user();
        $team = Team::find($teamId);

        // --- YEH NAYA CHECK ADD HUA HAI ---
        // Admin (ya koi bhi owner) apni hi team ko join nahi kar sakta
        if ($team && $team->user_id == $user->id) {
            $this->loadTeams(); // Sirf list refresh karein
            return;
        }
        // --- CHECK KHATAM ---

        // User ko team se attach karein
        if ($team && !$user->belongsToTeam($team)) {
            $user->teams()->attach($team, ['role' => 'editor']);

            if ($user->current_team_id === null) {
                $user->forceFill([
                    'current_team_id' => $team->id,
                ])->save();
            }
        }

        $this->loadTeams();
        $this->dispatch('teamJoined');
    }

    /**
     * Team chhorne (leave) ka function
     */
    public function leaveTeam($teamId)
    {
        $user = auth()->user();
        $team = Team::find($teamId);

        // --- YEH NAYA CHECK ADD HUA HAI ---
        // Admin (ya koi bhi owner) apni banayi hui team ko leave nahi kar sakta
        if ($team && $team->user_id == $user->id) {
             session()->flash('error', 'As the team owner, you cannot leave this team. You can only delete it from Team Settings.');
            return;
        }
        // --- CHECK KHATAM ---

        if ($team && $user->belongsToTeam($team)) {
            if ($user->current_team_id == $team->id) {
                session()->flash('error', 'You cannot leave your active team. Please switch teams first.');
                return;
            }
            $user->teams()->detach($team);
        }

        $this->loadTeams();
    }

    public function render()
    {
        return view('livewire.browse-teams');
    }
}
