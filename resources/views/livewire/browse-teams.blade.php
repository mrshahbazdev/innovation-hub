<div>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Browse and Join Teams') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8">

                    <!-- === YEH NAYA SECTION ADD KAREIN === -->
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-md" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif
                    <!-- === YAHAN TAK === -->

                    <h3 class="text-2xl font-semibold text-gray-900 mb-6">Available Teams</h3>

                    <ul class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($teams as $team)
                            <li class="col-span-1 bg-white rounded-lg shadow divide-y divide-gray-200 border">
                                <div class="w-full flex items-center justify-between p-6 space-x-6">
                                    <div class="flex-1 truncate">
                                        <div class="flex items-center space-x-3">
                                            @if(in_array($team->id, $myTeamIds))
                                                <a href="{{ route('teams.view', $team) }}" class="text-indigo-600 hover:text-indigo-900 text-lg font-medium truncate">
                                                    {{ $team->name }}
                                                </a>
                                            @else
                                                <h3 class="text-gray-900 text-lg font-medium truncate">{{ $team->name }}</h3>
                                            @endif
                                        </div>
                                        <p class="mt-1 text-gray-500 text-sm truncate">
                                            Owned by: {{ $team->owner->name }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex -mt-px divide-x divide-gray-200">

                                        @if(in_array($team->id, $myTeamIds))

                                            @if(auth()->user()->current_team_id == $team->id)
                                                <div class="flex-1 flex">
                                                    <span class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-gray-700 font-medium border border-transparent rounded-br-lg bg-gray-100">
                                                        (Your Active Team)
                                                    </span>
                                                </div>
                                            @else
                                                <div class="flex-1 flex">
                                                    <button wire:click="leaveTeam({{ $team->id }})"
                                                            wire:loading.attr="disabled"
                                                            class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-red-600 font-medium border border-transparent rounded-br-lg hover:bg-red-50">
                                                        Leave
                                                    </button>
                                                </div>
                                            @endif

                                        @else
                                            <div class="flex-1 flex">
                                                <button wire:click="joinTeam({{ $team->id }})"
                                                        wire:loading.attr="disabled"
                                                        class="relative w-0 flex-1 inline-flex items-center justify-center py-4 text-sm text-green-700 font-medium border border-transparent rounded-br-lg hover:bg-green-50">
                                                    Join Team
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </li>
                        @empty
                            <p class="text-gray-500 col-span-3">No global teams have been created by the admin yet.</p>
                        @endforelse
                    </ul>

                </div>
            </div>
        </div>
    </div>
</div>
