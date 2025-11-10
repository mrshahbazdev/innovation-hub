<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Idea;
use Illuminate\Support\Facades\DB;

class DashboardStats extends Component
{
    public $totalIdeas = 0;
    public $pendingReview = 0;
    public $pendingPricing = 0;
    public $approvedBudget = 0;

    /**
     * 'mount' function component load hote hi chalta hai
     */
    public function mount()
    {
        $user = auth()->user();
        $isAdmin = $user->is_admin;

        // --- YEH RAHA AAPKA FIX ---
        // Pehle currentTeam ko ek variable mein rakhein
        $currentTeam = $user->currentTeam;

        // Check karein ke team mojood hai ya nahi
        // Agar user naya hai aur team nahi hai, toh $teamId ko null set karein
        $teamId = $currentTeam ? $currentTeam->id : null;
        // --- FIX KHATAM ---

        // 1. Total Ideas
        $this->totalIdeas = Idea::when(! $isAdmin, function ($query) use ($teamId) {
                                    // Agar teamId null hai, toh yeh query 0 results degi (jo sahi hai)
                                    $query->where('team_id', $teamId);
                                })->count();

        // 2. Pending Review
        $this->pendingReview = Idea::where('status', 'new')
                                   ->when(! $isAdmin, function ($query) use ($teamId) {
                                       $query->where('team_id', $teamId);
                                   })->count();

        // 3. Pending Pricing
        $this->pendingPricing = Idea::where('status', 'pending_pricing')
                                    ->when(! $isAdmin, function ($query) use ($teamId) {
                                        $query->where('team_id', $teamId);
                                    })->count();

        // 4. Approved Budget
        $this->approvedBudget = Idea::where('status', 'approved')
                                    ->when(! $isAdmin, function ($query) use ($teamId) {
                                        $query->where('team_id', $teamId);
                                    })->sum('kosten');
    }

    public function render()
    {
        return view('livewire.dashboard-stats');
    }
}
