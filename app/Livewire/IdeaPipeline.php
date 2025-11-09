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
    public $status; // <-- YEH NAYI PROPERTY ADD HUI HAI

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

            // Database se values nikaal kar form properties mein daalein
            $this->schmerz = $idea->schmerz;
            $this->loesung = $idea->loesung;
            $this->kosten = $idea->kosten;
            $this->dauer = $idea->dauer;
            $this->prio_1 = $idea->prio_1;
            $this->prio_2 = $idea->prio_2;
            $this->umsetzung = $idea->umsetzung;
            $this->status = $idea->status; // <-- YEH NAYI LINE ADD HUI HAI
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
     * "Save" button dabane par (Updated Logic)
     */
    public function saveIdea($ideaId)
    {
        $idea = Idea::find($ideaId);
        if (!$idea) { return; }

        $user = auth()->user();
        $team = $user->currentTeam;
        $dataToSave = [];

        // Check karein agar user ke paas 'update-yellow' permission hai
        if ($user->hasTeamPermission($team, 'update-yellow')) {
            $validated = $this->validate([
                'schmerz' => 'nullable|integer|min:0|max:10',
                'prio_1' => 'nullable|numeric',
                'prio_2' => 'nullable|numeric',
                'umsetzung' => 'nullable|integer|min:0',
                // --- YEH LINE ADD HUI HAI ---
                'status' => 'required|in:new,pending_review,pending_pricing,approved,rejected,completed',
            ]);
            $dataToSave = array_merge($dataToSave, $validated);
        }

        // Check karein agar user ke paas 'update-red' permission hai
        if ($user->hasTeamPermission($team, 'update-red')) {
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

    // ... (sortBy function waisa hi) ...
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDir = 'asc';
        }
        $this->sortBy = $field;
    }

    /**
     * Page render karne wala function (waisa hi)
     */
    public function render()
    {
        $ideasQuery = Idea::where('team_id', auth()->user()->currentTeam->id);

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
