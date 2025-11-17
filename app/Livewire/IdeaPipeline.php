<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Idea;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Log;
#[Layout('layouts.app')]
class IdeaPipeline extends Component
{
    use WithPagination;

    public $editingIdeaId = null;
    public $search = '';
    public $filterStatus = '';
    public $sortBy = 'status';
    public $sortDir = 'asc';

    // --- FORM PROPERTIES ---
    public $schmerz;
    public $loesung;
    public $kosten;
    public $dauer;
    public $umsetzung;
    public $status;

    // --- 1. NAYI PROPERTIES ADD HUI HAIN ---
    public $problem_short;
    public $goal;
    public $problem_detail;

    // --- HOOKS (Pagination reset karne ke liye) ---
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }

    /**
     * "Edit" button dabane par
     */
    public function editIdea($ideaId)
    {
        try {
            \Log::info('Edit Idea called', ['idea_id' => $ideaId]);

            $idea = Idea::with(['team', 'user'])->find($ideaId);

            if (!$idea) {
                \Log::error('Idea not found', ['idea_id' => $ideaId]);
                return;
            }

            \Log::info('Idea found', ['idea_id' => $ideaId, 'team_id' => $idea->team_id]);

            $this->editingIdeaId = $ideaId;
            $this->schmerz = $idea->schmerz ?? 0;
            $this->loesung = $idea->loesung ?? '';
            $this->kosten = $idea->kosten ?? 0;
            $this->dauer = $idea->dauer ?? 0;
            $this->umsetzung = $idea->umsetzung ?? 0;
            $this->status = $idea->status ?? 'new';

            // YEH PROPERTIES PEHLE SE EXISTING HAIN
            $this->problem_short = $idea->problem_short ?? '';
            $this->goal = $idea->goal ?? '';
            $this->problem_detail = $idea->problem_detail ?? '';

            \Log::info('Edit Idea completed successfully');

        } catch (\Exception $e) {
            \Log::error('Edit Idea error', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            session()->flash('error', 'Error loading idea: ' . $e->getMessage());
        }
    }

    /**
     * "Cancel" button dabane par
     */
    public function cancelEdit()
    {
        $this->resetErrorBag();
        $this->editingIdeaId = null;
    }

    /**
     * "Save" button dabane par
     */
    /**
     * "Save" button dabane par
     */
    public function saveIdea($ideaId)
    {
        $idea = Idea::find($ideaId);
        if (!$idea) { return; }

        $user = auth()->user();

        // YEH LINE THEK KAREN: team idea wali team se leni hai, user ki current team se nahi
        $team = $idea->team; // <-- YAHAN CHANGE KARNA HAI
        $dataToSave = [];

        // --- Admin ya Owner core details edit kar sakta hai ---
        if ($user->is_admin || $user->id === $idea->user_id) {
            $validated = $this->validate([
                'problem_short' => 'required|string|max:100',
                'goal' => 'required|string|min:10', // <-- YEH BHI ADD KAREN
                'problem_detail' => 'required|string|min:20',
            ]);
            $dataToSave = array_merge($dataToSave, $validated);
        }

        // Team "Work-Bees" (Yellow) permissions
        // YAHAN BHI $team IDEA KI TEAM USE KAREN
        if (($team && $user->hasTeamPermission($team, 'update-yellow')) || $user->is_admin) {
            $validated = $this->validate([
                'schmerz' => 'nullable|integer|min:0|max:10',
                'umsetzung' => 'nullable|integer|min:0',
                'status' => 'required|in:new,pending_review,pending_pricing,approved,rejected,completed',
            ]);
            $dataToSave = array_merge($dataToSave, $validated);
        }

        // Team "Developer" (Red) permissions
        // YAHAN BHI $team IDEA KI TEAM USE KAREN
        if (($team && $user->hasTeamPermission($team, 'update-red')) || $user->is_admin) {
            $validated = $this->validate([
                'loesung' => 'nullable|string|max:1000',
                'kosten' => 'nullable|numeric|min:0',
                'dauer' => 'nullable|integer|min:0',
            ]);
            $dataToSave = array_merge($dataToSave, $validated);
        }

        if (!empty($dataToSave)) {
            $idea->update($dataToSave);
        }

        $this->editingIdeaId = null;
        $this->resetErrorBag();
    }

    /**
     * Idea delete karne ka function
     */
    public function deleteIdea($ideaId)
    {
        $idea = Idea::find($ideaId);
        if (!$idea) { return; }

        $user = auth()->user();

        // Sirf idea ka owner YA Super Admin hi delete kar sakta hai
        if ($user->id === $idea->user_id || $user->is_admin) {
            $idea->delete();
            session()->flash('message', 'Idea deleted successfully.');
        } else {
            session()->flash('error', 'You do not have permission to delete this idea.');
        }

        $this->resetPage();
    }

    /**
     * Page render karne wala function
     */
    public function render()
    {
        $ideasQuery = Idea::query();
        $user = auth()->user();

        if (! $user->is_admin) {
            $currentTeam = $user->currentTeam;
            $teamId = $currentTeam ? $currentTeam->id : null;
            $ideasQuery->where('team_id', $teamId);
        }

        if ($this->filterStatus) {
            $ideasQuery->where('status', $this->filterStatus);
        }

        if ($this->search) {
            $ideasQuery->where(function($query) {
                $query->where('problem_short', 'like', '%'.$this->search.'%')
                      ->orWhere('problem_detail', 'like', '%'.$this->search.'%');
            });
        }

        $ideasQuery->orderBy($this->sortBy, $this->sortDir);
        $ideas = $ideasQuery->paginate(15);

        return view('livewire.idea-pipeline', [
            'ideas' => $ideas,
        ]);
    }
}
