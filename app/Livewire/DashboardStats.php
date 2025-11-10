<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Idea;
use Illuminate\Support\Facades\DB;

class DashboardStats extends Component
{
    // Properties ko hold karne ke liye
    public $totalIdeas = 0;
    public $pendingReview = 0;
    public $pendingPricing = 0;
    public $approvedBudget = 0;

    /**
     * 'mount' function component load hote hi chalta hai
     * Hum yahan apne saare stats calculate karenge
     */
    public function mount()
    {
        // Check karein ke user admin hai ya nahi
        $isAdmin = auth()->user()->is_admin;
        $teamId = auth()->user()->currentTeam->id;

        // 1. Total Ideas
        $this->totalIdeas = Idea::when(! $isAdmin, function ($query) use ($teamId) {
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
        // Sirf view file ko render karein
        return view('livewire.dashboard-stats');
    }
}
