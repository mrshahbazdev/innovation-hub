<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Idea;
use Livewire\Attributes\Layout;
use Livewire\WithPagination;

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
    public $prio_1;
    public $prio_2;
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
        $idea = Idea::find($ideaId);
        if ($idea) {
            $this->editingIdeaId = $ideaId;
            $this->schmerz = $idea->schmerz;
            $this->loesung = $idea->loesung;
            $this->kosten = $idea->kosten;
            $this->dauer = $idea->dauer;
            $this->prio_1 = $idea->prio_1;
            $this->prio_2 = $idea->prio_2;
            $this->umsetzung = $idea->umsetzung;
            $this->status = $idea->status;

            // --- 2. NAYI PROPERTIES LOAD HONGY ---
            $this->problem_short = $idea->problem_short;
            $this->goal = $idea->goal;
            $this->problem_detail = $idea->problem_detail;
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
    public function saveIdea($ideaId)
    {
        $idea = Idea::find($ideaId);
        if (!$idea) { return; }

        $user = auth()->user();
        $team = $user->currentTeam;
        $dataToSave = [];

        // --- 3. NAYA LOGIC: Admin ya Owner core details edit kar sakta hai ---
        if ($user->is_admin || $user->id === $idea->user_id) {
            $validated = $this->validate([
                'problem_short' => 'required|string|max:100',
                'goal' => 'required|string|min:10',
                'problem_detail' => 'required|string|min:20',
            ]);
            $dataToSave = array_merge($dataToSave, $validated);
        }

        // Team "Work-Bees" (Yellow) permissions
        if ($user->hasTeamPermission($team, 'update-yellow') || $user->is_admin) {
            $validated = $this->validate([
                'schmerz' => 'nullable|integer|min:0|max:10',
                'prio_1' => 'nullable|numeric',
                'prio_2' => 'nullable|numeric',
                'umsetzung' => 'nullable|integer|min:0',
                'status' => 'required|in:new,pending_review,pending_pricing,approved,rejected,completed',
            ]);
            $dataToSave = array_merge($dataToSave, $validated);
        }

        // Team "Developer" (Red) permissions
        if ($user->hasTeamPermission($team, 'update-red') || $user->is_admin) {
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
