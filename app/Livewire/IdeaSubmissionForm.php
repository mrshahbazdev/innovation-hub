<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Idea;
use App\Models\Team;
use Livewire\Attributes\Rule;
// Layout attribute hata diya gaya hai

class IdeaSubmissionForm extends Component
{
    public Team $team;

    public $currentStep = 1;

    // Rules ab yahan properties par define hain
    #[Rule('required|string|max:100')]
    public $problem_short = '';

    #[Rule('required|string|min:10')]
    public $goal = '';

    #[Rule('required|string|min:20')]
    public $problem_detail = '';

    #[Rule('nullable|email')]
    public $contact_info = '';

    /**
     * Component load hone par
     */
    public function mount(Team $team)
    {
        $this->team = $team;
        $this->contact_info = auth()->user()->email;
    }

    // --- YEH HAIN TAMAM FIXED FUNCTIONS ---
    public function firstStepSubmit()
    {
        $this->validateOnly('problem_short'); // 'validate' ko 'validateOnly' se badal diya
        $this->currentStep = 2;
    }

    public function secondStepSubmit()
    {
        $this->validateOnly('goal'); // 'validate' ko 'validateOnly' se badal diya
        $this->currentStep = 3;
    }

    public function thirdStepSubmit()
    {
        $this->validateOnly('problem_detail'); // 'validate' ko 'validateOnly' se badal diya
        $this->currentStep = 4;
    }

    public function previousStep()
    {
        $this->currentStep--;
    }

    /**
     * UPDATED Submit Function (Ab Step 4 hai)
     */
    public function submitForm()
    {
        // Yahan hum 'validate()' istemal kar sakte hain kyunke hum tamam rules ko ek saath validate kar rahe hain
        $validatedData = $this->validate([
            'problem_short' => 'required|string|max:100',
            'goal' => 'required|string|min:10',
            'problem_detail' => 'required|string|min:20',
            'contact_info' => 'nullable|email',
        ]);

        // Create the Idea
        Idea::create([
            'team_id' => $this->team->id,
            'user_id' => auth()->id(),
            'submitter_type' => 'user',
            'contact_info' => $this->contact_info,
            'problem_short' => $this->problem_short,
            'goal' => $this->goal,
            'problem_detail' => $this->problem_detail,
            'status' => 'new',
        ]);

        // User ko thank you page par bhej dein
        return $this->redirect(route('thank-you'), navigate: true);
    }

    public function render()
    {
        return view('livewire.idea-submission-form');
    }
}
