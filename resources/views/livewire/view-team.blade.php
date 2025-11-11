<div>
    {{-- Page Header (Team ka naam dikhayega) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Team: <span class="text-indigo-600">{{ $team->name }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            {{-- Submit Idea Form --}}
            <div class="mb-8">
                <h3 class="text-2xl font-semibold text-gray-900 mb-4">Submit a New Idea to this Team</h3>
                @livewire('idea-submission-form', ['team' => $team])
            </div>


        </div>
    </div>
</div>
