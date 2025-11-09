<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Hamara naya Livewire component yahan call karein --}}
            @livewire('dashboard-stats')

            {{-- Yeh Jetstream ka default Welcome message hai, isse hum yahan se hata sakte hain ya rehne de sakte hain --}}
            {{-- <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg mt-8">
                <x-welcome />
            </div> --}}

        </div>
    </div>
</x-app-layout>
