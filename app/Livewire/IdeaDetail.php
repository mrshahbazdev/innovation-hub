<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Idea;
use App\Models\IdeaComment;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Rule;
// Title attribute ko yahan se bhi hata sakte hain agar aur kahin istemal nahi ho raha
// use Livewire\Attributes\Title;
use Spatie\Activitylog\Models\Activity;

#[Layout('layouts.app')]
class IdeaDetail extends Component
{
    public Idea $idea;

    #[Rule('required|string|min:3')]
    public $newComment = '';

    public $activities;

    // --- YEH RAHA SAHI (FIXED) CODE ---
    // #[Title] attribute ko HATA diya gaya hai
    public function title()
    {
        // Livewire is function ko khud dhoond lega
        return 'Idea: ' . $this->idea->problem_short;
    }
    // --- YAHAN TAK ---

    /**
     * Component ke mount (load) hone par
     */
    public function mount(Idea $idea)
    {
        $this->idea = $idea;
        $this->loadData(); // Data load karne ke liye naya function
    }

    /**
     * Naya comment add karne ka function
     */
    public function addComment()
    {
        $this->validate();

        $this->idea->comments()->create([
            'user_id' => auth()->id(),
            'body' => $this->newComment,
        ]);

        $this->newComment = '';
        $this->loadData(); // Comments aur activities ko refresh karein

        session()->flash('message', 'Comment added successfully.');
    }

    /**
     * Data ko load ya refresh karne ka helper function
     */
    public function loadData()
    {
        // Idea ke comments aur user ko load karein
        $this->idea->load('comments', 'comments.user');

        // Idea ki activities aur causer (user) ko load karein
        $this->activities = Activity::where('subject_type', Idea::class)
                                ->where('subject_id', $this->idea->id)
                                ->with('causer') // User jisne activity ki
                                ->orderBy('created_at', 'desc')
                                ->get();
    }

    public function render()
    {
        return view('livewire.idea-detail');
    }
}
