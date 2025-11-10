<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Team;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // Admin layout istemal karega
class ViewTeam extends Component
{
    public Team $team;

    public function mount(Team $team)
    {
        // Check karein ke user is team ka member hai ya nahi
        if (! auth()->user()->belongsToTeam($team)) {
            // Agar nahi hai, toh usse access na dein
            abort(403);
        }

        $this->team = $team;
    }

    public function render()
    {
        return view('livewire.view-team');
    }
}