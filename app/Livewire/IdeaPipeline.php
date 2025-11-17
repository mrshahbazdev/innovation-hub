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

    // --- FORM PROPERTIES (Empty values se initialize) ---
    public $schmerz = '';
    public $loesung = '';
    public $kosten = '';
    public $dauer = '';
    public $umsetzung = '';
    public $status = '';

    // --- NAYI PROPERTIES (Empty values se initialize) ---
    public $problem_short = '';
    public $goal = '';
    public $problem_detail = '';

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
            $idea = Idea::with('team')->find($ideaId);

            if (!$idea) {
                session()->flash('error', 'Idea not found.');
                return;
            }

            $this->editingIdeaId = $ideaId;

            // Properties ko safe way mein set karen
            $this->schmerz = $idea->schmerz ?? 0;
            $this->loesung = $idea->loesung ?? '';
            $this->kosten = $idea->kosten ?? 0;
            $this->dauer = $idea->dauer ?? 0;
            $this->umsetzung = $idea->umsetzung ?? 0;
            $this->status = $idea->status ?? 'new';

            // NAYI PROPERTIES
            $this->problem_short = $idea->problem_short ?? '';
            $this->goal = $idea->goal ?? '';
            $this->problem_detail = $idea->problem_detail ?? '';

        } catch (\Exception $e) {
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

        // Properties ko reset karen
        $this->reset(['schmerz', 'loesung', 'kosten', 'dauer', 'umsetzung', 'status',
                     'problem_short', 'goal', 'problem_detail']);
    }

    /**
     * "Save" button dabane par
     */
    public function saveIdea($ideaId)
    {
        try {
            $idea = Idea::with('team')->find($ideaId);
            if (!$idea) {
                session()->flash('error', 'Idea not found.');
                return;
            }

            $user = auth()->user();

            // YEH LINE THEK KARI HAI: team idea wali team se leni hai
            $team = $idea->team;
            $dataToSave = [];

            // --- Admin ya Owner core details edit kar sakta hai ---
            if ($user->is_admin || $user->id === $idea->user_id) {
                $validated = $this->validate([
                    'problem_short' => 'required|string|max:100',
                    'goal' => 'required|string|min:10',
                    'problem_detail' => 'required|string|min:20',
                ]);
                $dataToSave = array_merge($dataToSave, $validated);
            }

            // Team "Work-Bees" (Yellow) permissions
            // YAHAN NULL CHECK ADD KIYA HAI
            if (($team && $user->hasTeamPermission($team, 'update-yellow')) || $user->is_admin) {
                $validated = $this->validate([
                    'schmerz' => 'nullable|integer|min:0|max:10',
                    'umsetzung' => 'nullable|integer|min:0',
                    'status' => 'required|in:new,pending_review,pending_pricing,approved,rejected,completed',
                ]);
                $dataToSave = array_merge($dataToSave, $validated);
            }

            // Team "Developer" (Red) permissions
            // YAHAN NULL CHECK ADD KIYA HAI
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
                session()->flash('message', 'Idea updated successfully.');
            } else {
                session()->flash('error', 'No changes to save or no permission to edit.');
            }

            $this->editingIdeaId = null;
            $this->resetErrorBag();

            // Properties ko reset karen
            $this->reset(['schmerz', 'loesung', 'kosten', 'dauer', 'umsetzung', 'status',
                         'problem_short', 'goal', 'problem_detail']);

        } catch (\Exception $e) {
            session()->flash('error', 'Error saving idea: ' . $e->getMessage());
        }
    }

    /**
     * Idea delete karne ka function
     */
    public function deleteIdea($ideaId)
    {
        $idea = Idea::find($ideaId);
        if (!$idea) {
            session()->flash('error', 'Idea not found.');
            return;
        }

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
                      ->orWhere('problem_detail', 'like', '%'.$this->search.'%')
                      ->orWhere('goal', 'like', '%'.$this->search.'%');
            });
        }

        $ideasQuery->orderBy($this->sortBy, $this->sortDir);
        $ideas = $ideasQuery->paginate(15);

        return view('livewire.idea-pipeline', [
            'ideas' => $ideas,
        ]);
    }
}
