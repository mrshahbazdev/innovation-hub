<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Idea;
use Livewire\Attributes\Rule; // Livewire 3 ka naya validation attribute
use Livewire\Attributes\Layout;
#[Layout('layouts.guest')]
class IdeaSubmissionForm extends Component
{
    // Form ka current step manage karne ke liye
    public $currentStep = 1;

    // --- Step 1 Data ---
    #[Rule('required|string|max:100')] // 4 lafzon ki had 100 characters rakhi hai
    public $problem_short = '';

    // --- Step 2 Data ---
    #[Rule('required|string|min:10')]
    public $goal = '';

    // --- Step 3 Data ---
    #[Rule('required|string|min:20')]
    public $problem_detail = '';

    // --- Step 4 Data ---
    #[Rule('nullable|email')]
    public $contact_info = '';

    /**
     * Step 1 ki validation aur agle step par jaana
     */
    public function firstStepSubmit()
    {
        $this->validateOnly('problem_short');
        $this->currentStep = 2;
    }

    /**
     * Step 2 ki validation aur agle step par jaana
     */
    public function secondStepSubmit()
    {
        $this->validateOnly('goal');
        $this->currentStep = 3;
    }

    /**
     * Step 3 ki validation aur agle step par jaana
     */
    public function thirdStepSubmit()
    {
        $this->validateOnly('problem_detail');
        $this->currentStep = 4;
    }

    /**
     * Form ko pichle step par le jaana
     */
    public function previousStep()
    {
        $this->currentStep--;
    }

    /**
     * Aakhri step (Step 4) - Form submit karna aur database mein save karna
     */
    public function submitForm()
    {
        $this->validateOnly('contact_info');

        // Data ko database mein save karein
        Idea::create([
            // Hum farz karte hain ke public submissions team ID 1 ko jayengi
            // Jetstream mein pehli team hamesha ID 1 hoti hai (Aapki Admin team)
            'team_id' => 1,
            'user_id' => auth()->id(), // Agar user login hai, toh ID save hogi, warna null
            'submitter_type' => auth()->check() ? 'user' : 'visitor',
            'contact_info' => $this->contact_info,

            // Form se aaya hua data
            'problem_short' => $this->problem_short,
            'goal' => $this->goal,
            'problem_detail' => $this->problem_detail,

            'status' => 'new', // Naya status
        ]);

        // Success step dikhayein
        return $this->redirect(route('thank-you'), navigate: true);
    }

    public function render()
    {
        return view('livewire.idea-submission-form');
    }
}
