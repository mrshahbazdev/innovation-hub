<div class="max-w-2xl mx-auto p-6 bg-white shadow-lg rounded-lg mt-10">

    {{-- STEPPER / PROGRESS BAR --}}
    <div class="mb-6">
        <div class="flex items-center">
            <div class="flex-1 text-center {{ $currentStep >= 1 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-10 h-10 mx-auto rounded-full border-2 {{ $currentStep >= 1 ? 'border-blue-600' : 'border-gray-400' }} flex items-center justify-center">1</div>
                <p class="mt-1 text-sm">Problem</p>
            </div>
            <div class="flex-1 border-t-2 {{ $currentStep >= 2 ? 'border-blue-600' : 'border-gray-400' }}"></div>
            <div class="flex-1 text-center {{ $currentStep >= 2 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-10 h-10 mx-auto rounded-full border-2 {{ $currentStep >= 2 ? 'border-blue-600' : 'border-gray-400' }} flex items-center justify-center">2</div>
                <p class="mt-1 text-sm">Goal</p>
            </div>
            <div class="flex-1 border-t-2 {{ $currentStep >= 3 ? 'border-blue-600' : 'border-gray-400' }}"></div>
            <div class="flex-1 text-center {{ $currentStep >= 3 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-10 h-10 mx-auto rounded-full border-2 {{ $currentStep >= 3 ? 'border-blue-600' : 'border-gray-400' }} flex items-center justify-center">3</div>
                <p class="mt-1 text-sm">Details</p>
            </div>
            <div class="flex-1 border-t-2 {{ $currentStep >= 4 ? 'border-blue-600' : 'border-gray-400' }}"></div>
            <div class="flex-1 text-center {{ $currentStep >= 4 ? 'text-blue-600' : 'text-gray-400' }}">
                <div class="w-10 h-10 mx-auto rounded-full border-2 {{ $currentStep >= 4 ? 'border-blue-600' : 'border-gray-400' }} flex items-center justify-center">4</div>
                <p class="mt-1 text-sm">Contact</p>
            </div>
        </div>
    </div>


    {{-- STEP 1: Problem --}}
    @if ($currentStep == 1)
        <div wire:key="step1">
            <h3 class="text-xl font-semibold mb-4">Step 1: Your Problem</h3>
            <p class="text-gray-600 mb-4">Describe your problem in 4-5 words (e.g., "Website login is difficult").</p>
            <form wire:submit="firstStepSubmit">
                <label for="problem_short" class="block text-sm font-medium text-gray-700">Problem:</label>
                <input type="text" id="problem_short" wire:model.live="problem_short" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                @error('problem_short') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <div class="text-right mt-6">
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Next</button>
                </div>
            </form>
        </div>
    @endif

    {{-- STEP 2: Goal --}}
    @if ($currentStep == 2)
        <div wire:key="step2">
            <h3 class="text-xl font-semibold mb-4">Step 2: Your Goal</h3>
            <p class="text-gray-600 mb-4">What needs to change for you to be satisfied?</p>
            <form wire:submit="secondStepSubmit">
                <label for="goal" class="block text-sm font-medium text-gray-700">Goal:</label>
                <textarea id="goal" wire:model.live="goal" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                @error('goal') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="previousStep" class="py-2 px-4 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Previous</button>
                    <button type="submit" class="py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Next</button>
                </div>
            </form>
        </div>
    @endif

    {{-- STEP 3: Details --}}
    @if ($currentStep == 3)
        <div wire:key="step3">
            <h3 class="text-xl font-semibold mb-4">Step 3: Problem Details</h3>
            <p class="text-gray-600 mb-4">Describe your problem or pain point in as much detail as possible here.</p>
            <form wire:submit="thirdStepSubmit">
                <label for="problem_detail" class="block text-sm font-medium text-gray-700">Details:</label>
                <textarea id="problem_detail" wire:model.live="problem_detail" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                @error('problem_detail') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="previousStep" class="py-2 px-4 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Previous</button>
                    <button type="submit" class="py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">Next</button>
                </div>
            </form>
        </div>
    @endif

    {{-- STEP 4: Contact --}}
    @if ($currentStep == 4)
        <div wire:key="step4">
            <h3 class="text-xl font-semibold mb-4">Step 4: Contact Information (Optional)</h3>
            <p class="text-gray-600 mb-4">If you are a visitor, please provide your email so we can contact you. (Not required if you are logged in).</p>
            <form wire:submit="submitForm">
                <label for="contact_info" class="block text-sm font-medium text-gray-700">Email Address (Optional):</label>
                <input type="email" id="contact_info" wire:model.live="contact_info" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="you@example.com">
                @error('contact_info') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                <div class="flex justify-between mt-6">
                    <button type="button" wire:click="previousStep" class="py-2 px-4 border rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">Previous</button>

                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                        <span wire:loading.remove wire:target="submitForm">Submit Idea</span>
                        <span wire:loading wire:target="submitForm">Submitting...</span>
                    </button>
                </div>
            </form>
        </div>
    @endif


</div>
