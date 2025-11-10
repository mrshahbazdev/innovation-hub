<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // Admin layout istemal karega
class BrowseTeams extends Component
{
    public $teams; // Tamam global teams hold karega
    public $myTeamIds = []; // User jin teams mein hai, unki IDs

    /**
     * Component load hone par
     */
    public function mount()
    {
        $this->loadTeams();
    }

    /**
     * Tamam teams aur user ki teams ko load karein
     */
    public function loadTeams()
    {
        // Sirf 'Global' teams load karein (personal nahi)
        $this->teams = Team::where('personal_team', false)->get();

        // User ki maujooda teams ki IDs load karein
        $this->myTeamIds = auth()->user()->teams()->pluck('id')->toArray();
    }

    /**
     * Team join karne ka function
     */
    public function joinTeam($teamId)
    {
        $user = auth()->user();
        $team = Team::find($teamId);

        // User ko team se attach karein (Jetstream ka default role 'editor' hai)
        if ($team && !$user->belongsToTeam($team)) {
            $user->teams()->attach($team, ['role' => 'editor']);
        }

        // List ko refresh karein
        $this->loadTeams();
    }

    /**
     * Team chhorne (leave) ka function
     */
    public function leaveTeam($teamId)
    {
        $user = auth()->user();
        $team = Team::find($teamId);

        // User ko team se detach karein
        if ($team && $user->belongsToTeam($team)) {
            // Hum user ko uski current active team chhorne nahi denge
            if ($user->current_team_id == $team->id) {
                // (Aap yahan error dikha sakte hain, lekin abhi ke liye skip karte hain)
                return;
            }
            $user->teams()->detach($team);
        }

        // List ko refresh karein
        $this->loadTeams();
    }

    public function render()
    {
        return view('livewire.browse-teams');
    }
}
