<div>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Site Logo Manager') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">

                    <h3 class="text-lg font-medium text-gray-900 mb-4">Update Site Logo</h3>

                    {{-- Success Message --}}
                    @if (session('message'))
                        <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md">
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit="saveLogo">

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700">Logo Preview</label>

                            @if ($logo)
                                {{-- Naye (temporary) logo ka preview --}}
                                <img src="{{ $logo->temporaryUrl() }}" class="mt-2 h-24 w-auto border p-2 rounded">
                            @else
                                {{-- Purane (current) logo ka preview --}}
                                <img src="{{ $currentLogoUrl }}" class="mt-2 h-24 w-auto border p-2 rounded">
                            @endif
                        </div>

                        <div class="mb-4">
                            <label for="logo" class="block text-sm font-medium text-gray-700">Choose new logo (PNG format recommended)</label>
                            <input type="file" id="logo" wire:model="logo" class="mt-1 block w-full text-sm text-gray-500
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100">

                            {{-- Loading indicator --}}
                            <div wire:loading wire:target="logo" class="text-sm text-gray-500 mt-1">Uploading...</div>

                            @error('logo') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="text-right">
                            <button type="submit" wire:loading.attr="disabled"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring focus:ring-indigo-300 disabled:opacity-25 transition">
                                <span wire:loading.remove wire:target="saveLogo">
                                    Save Logo
                                </span>
                                <span wire:loading wire:target="saveLogo">
                                    Saving...
                                </span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
