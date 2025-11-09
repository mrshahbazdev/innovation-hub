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
        // Sirf current team ka data fetch karein
        $teamId = auth()->user()->currentTeam->id;

        // 1. Total Ideas
        $this->totalIdeas = Idea::where('team_id', $teamId)->count();

        // 2. Pending Review (Jo 'new' hain)
        $this->pendingReview = Idea::where('team_id', $teamId)
                                   ->where('status', 'new')
                                   ->count();

        // 3. Pending Pricing (Jo 'pending_pricing' hain)
        $this->pendingPricing = Idea::where('team_id', $teamId)
                                    ->where('status', 'pending_pricing')
                                    ->count();

        // 4. Approved Budget (Approved projects ki 'kosten' ka sum)
        $this->approvedBudget = Idea::where('team_id', $teamId)
                                    ->where('status', 'approved')
                                    ->sum('kosten');
    }

    public function render()
    {
        // Sirf view file ko render karein
        return view('livewire.dashboard-stats');
    }
}
