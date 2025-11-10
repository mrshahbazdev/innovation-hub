<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Idea;
use App\Models\Team; // <-- 1. Import Team model
use Livewire\Attributes\Rule;
use Livewire\Attributes\Layout;

#[Layout('layouts.app')] // <-- 2. Change layout to 'layouts.app'
class IdeaSubmissionForm extends Component
{
    public $currentStep = 1;

    // --- 3. NEW PROPERTIES ---
    public $teams; // To hold all teams
    #[Rule('required|exists:teams,id')]
    public $selectedTeamId = null; // To store the user's choice

    // --- OLD PROPERTIES (Validation moved to submitForm) ---
    public $problem_short = '';
    public $goal = '';
    public $problem_detail = '';
    public $contact_info = '';

    /**
     * 4. Load all available teams when the component starts
     */
    public function mount()
    {
        $this->teams = Team::all();

        // Auto-fill contact info if user is logged in
        $this->contact_info = auth()->user()->email;
    }

    public function firstStepSubmit()
    {
        $this->validate(['selectedTeamId']); // Validate Step 1
        $this->currentStep = 2;
    }

    public function secondStepSubmit()
    {
        $this->validate(['problem_short']); // Validate Step 2
        $this->currentStep = 3;
    }

    public function thirdStepSubmit()
    {
        $this->validate(['goal']); // Validate Step 3
        $this->currentStep = 4;
    }

    public function fourthStepSubmit()
    {
        $this->validate(['problem_detail']); // Validate Step 4
        $this->currentStep = 5;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    /**
     * 5. UPDATED Submit Function (Now Step 5)
     */
    public function submitForm()
    {
        // Validate all fields at the end
        $validatedData = $this->validate([
            'selectedTeamId' => 'required|exists:teams,id',
            'problem_short' => 'required|string|max:100',
            'goal' => 'required|string|min:10',
            'problem_detail' => 'required|string|min:20',
            'contact_info' => 'nullable|email',
        ]);

        // Create the Idea
        Idea::create([
            'team_id' => $this->selectedTeamId, // Use the selected team
            'user_id' => auth()->id(),
            'submitter_type' => 'user',
            'contact_info' => $this->contact_info,
            'problem_short' => $this->problem_short,
            'goal' => $this->goal,
            'problem_detail' => $this->problem_detail,
            'status' => 'new',
        ]);

        // Attach the user to this team (so they can see it later)
        auth()->user()->teams()->syncWithoutDetaching($this->selectedTeamId);

        // Redirect to the thank-you page
        return $this->redirect(route('thank-you'), navigate: true);
    }

    public function render()
    {
        return view('livewire.idea-submission-form');
    }
}
