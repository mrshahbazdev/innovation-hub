<div>
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Innovation Pipeline') }}
            </h2>
        </div>
    </header>

    <div class="bg-white p-4 sm:p-6 shadow-md">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2">
                <input type="text" wire:model.live.debounce.300ms="search"
                       class="block w-full border-gray-300 rounded-md shadow-sm"
                       placeholder="Search by problem...">
            </div>
            <div>
                <select wire:model.live="filterStatus"
                        class="block w-full border-gray-300 rounded-md shadow-sm">
                    <option value="">All Statuses</option>
                    <option value="new">New</option>
                    <option value="pending_review">Pending Review</option>
                    <option value="pending_pricing">Pending Pricing</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-4">
        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('message') }}</span>
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif
    </div>

    <div class="hidden sm:block w-full">
        <div class="bg-white overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0 z-10">
                        <tr>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Idea / Problem</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <button wire:click="sortBy('status')" class="flex items-center space-x-1">
                                    <span>Status</span>
                                    @if ($sortBy === 'status')
                                        <span class="text-xs">@if ($sortDir === 'asc') &uarr; @else &darr; @endif</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-yellow-100">
                                <button wire:click="sortBy('schmerz')" class="flex items-center space-x-1">
                                    <span>Schmerz</span>
                                    @if ($sortBy === 'schmerz')
                                        <span class="text-xs">@if ($sortDir === 'asc') &uarr; @else &darr; @endif</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-red-100">Lösung</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-red-100">
                                <button wire:click="sortBy('kosten')" class="flex items-center space-x-1">
                                    <span>Kosten</span>
                                    @if ($sortBy === 'kosten')
                                        <span class="text-xs">@if ($sortDir === 'asc') &uarr; @else &darr; @endif</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-red-100">
                                <button wire:click="sortBy('dauer')" class="flex items-center space-x-1">
                                    <span>Dauer</span>
                                    @if ($sortBy === 'dauer')
                                        <span class="text-xs">@if ($sortDir === 'asc') &uarr; @else &darr; @endif</span>
                                    @endif
                                </button>
                            </th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-yellow-100">Prio 1</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-yellow-100">Prio 2</th>
                            <th class="px-3 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider bg-yellow-100">
                                <button wire:click="sortBy('umsetzung')" class="flex items-center space-x-1">
                                    <span>Umsetzung</span>
                                    @if ($sortBy === 'umsetzung')
                                        <span class="text-xs">@if ($sortDir === 'asc') &uarr; @else &darr; @endif</span>
                                    @endif
                                </button>
                            </th>
                            <th class="relative px-3 py-3"><span class="sr-only">Actions</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($ideas as $idea)
                            <tr wire:key="desktop-{{ $idea->id }}">
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @if ($editingIdeaId === $idea->id && (auth()->user()->is_admin || auth()->user()->id === $idea->user_id))
                                        <input type="text" wire:model="problem_short" class="block w-full border-gray-300 rounded-md shadow-sm text-sm">
                                        @error('problem_short') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        <a href="{{ route('idea.detail', $idea) }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                            {{ $idea->problem_short }}
                                        </a>
                                    @endif

                                    @if ($editingIdeaId === $idea->id && (auth()->user()->is_admin || auth()->user()->id === $idea->user_id))
                                        <textarea wire:model="problem_detail" class="mt-2 block w-full border-gray-300 rounded-md shadow-sm text-sm" rows="2"></textarea>
                                        @error('problem_detail') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        <div class="text-sm text-gray-500 truncate" style="max-width: 250px;">{{ $idea->problem_detail }}</div>
                                    @endif
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap">
                                    @if ($editingIdeaId === $idea->id)
                                        <select wire:model="status"
                                                class="block w-full border-gray-300 rounded-md shadow-sm text-sm"
                                                @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                                            <option value="new">New</option>
                                            <option value="pending_review">Pending Review</option>
                                            <option value="pending_pricing">Pending Pricing</option>
                                            <option value="approved">Approved</option>
                                            <option value="rejected">Rejected</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($idea->status == 'new') bg-blue-100 text-blue-800 @endif
                                            @if($idea->status == 'pending_review') bg-yellow-100 text-yellow-800 @endif
                                            @if($idea->status == 'pending_pricing') bg-purple-100 text-purple-800 @endif
                                            @if($idea->status == 'approved') bg-green-100 text-green-800 @endif
                                            @if($idea->status == 'rejected') bg-red-100 text-red-800 @endif
                                            @if($idea->status == 'completed') bg-gray-100 text-gray-800 @endif
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $idea->status)) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 bg-yellow-50">
                                    @if ($editingIdeaId === $idea->id)
                                        <input type="number" wire:model="schmerz" class="block w-20 border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                                        @error('schmerz') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        {{ $idea->schmerz ?? '---' }}
                                    @endif
                                </td>
                                <td class="px-3 py-2 text-sm text-gray-500 bg-red-50">
                                    @if ($editingIdeaId === $idea->id)
                                        <textarea wire:model="loesung" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-red'))></textarea>
                                        @error('loesung') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        {{ $idea->loesung ?? '---' }}
                                    @endif
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 bg-red-50">
                                    @if ($editingIdeaId === $idea->id)
                                        <input type="text" wire:model="kosten" class="block w-24 border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-red'))>
                                        @error('kosten') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        ${{ number_format($idea->kosten, 2) ?? '---' }}
                                    @endif
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 bg-red-50">
                                    @if ($editingIdeaId === $idea->id)
                                        <input type="number" wire:model="dauer" class="block w-20 border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-red'))>
                                        @error('dauer') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        {{ $idea->dauer ?? '---' }} days
                                    @endif
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 bg-yellow-50">
                                    @if ($editingIdeaId === $idea->id)
                                        <input type="text" wire:model="prio_1" class="block w-20 border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                                        @error('prio_1') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        {{ $idea->prio_1 ?? '---' }}
                                    @endif
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900 bg-yellow-50">
                                    @if ($editingIdeaId === $idea->id)
                                        <input type="text" wire:model="prio_2" class="block w-20 border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                                        @error('prio_2') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        {{ $idea->prio_2 ?? '---' }}
                                    @endif
                                </td>
                                <td class="px-3 py-2 whitespace-nowrap text-sm font-bold text-gray-900 bg-yellow-50">
                                    @if ($editingIdeaId === $idea->id)
                                        <input type="number" wire:model="umsetzung" class="block w-20 border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                                        @error('umsetzung') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                    @else
                                        {{ $idea->umsetzung ?? '---' }}
                                    @endif
                                </td>

                                <td class="px-3 py-2 whitespace-nowrap text-right text-sm font-medium">
                                    @if ($editingIdeaId === $idea->id)
                                        <button wire:click="saveIdea({{ $idea->id }})" class="text-green-600 hover:text-green-900">Save</button>
                                        <button wire:click="cancelEdit" class="text-gray-600 hover:text-gray-900 ml-2">Cancel</button>
                                    @else
                                        @php
                                            $user = auth()->user();
                                            $team = $user->currentTeam;

                                            $canEditAnything = $user->is_admin ||
                                                               $user->id === $idea->user_id ||
                                                               ($team && $user->hasTeamPermission($team, 'update-yellow')) ||
                                                               ($team && $user->hasTeamPermission($team, 'update-red'));

                                            $canDelete = $user->is_admin || $user->id === $idea->user_id;
                                        @endphp

                                        @if ($canEditAnything)
                                            <button wire:click="editIdea({{ $idea->id }})" class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        @endif

                                        @if ($canDelete)
                                            <button
                                                wire:click="deleteIdea({{ $idea->id }})"
                                                wire:confirm="Are you sure you want to delete this idea?"
                                                class="text-red-600 hover:text-red-900 ml-2">
                                                Delete
                                            </button>
                                        @endif
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="10" class="px-6 py-12 text-center">
                                <h3 class="text-lg font-medium text-gray-700">No Ideas Found</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Your filters returned no results.
                                </p>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t">
                {{ $ideas->links() }}
            </div>
        </div>
    </div>

    <div class="block sm:hidden p-4 space-y-4">

        @forelse ($ideas as $idea)
            <div wire:key="mobile-{{ $idea->id }}" class="bg-white shadow rounded-lg p-4">

                <div class="flex justify-between items-center mb-3">
                    <div>
                        @if ($editingIdeaId === $idea->id)
                            <select wire:model="status"
                                    class="block w-full border-gray-300 rounded-md shadow-sm text-sm"
                                    @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                                <option value="new">New</option>
                                <option value="pending_review">Pending Review</option>
                                <option value="pending_pricing">Pending Pricing</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="completed">Completed</option>
                            </select>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @if($idea->status == 'new') bg-blue-100 text-blue-800 @endif
                                @if($idea->status == 'pending_review') bg-yellow-100 text-yellow-800 @endif
                                @if($idea->status == 'pending_pricing') bg-purple-100 text-purple-800 @endif
                                @if($idea->status == 'approved') bg-green-100 text-green-800 @endif
                                @if($idea->status == 'rejected') bg-red-100 text-red-800 @endif
                                @if($idea->status == 'completed') bg-gray-100 text-gray-800 @endif
                            ">
                                {{ ucfirst(str_replace('_', ' ', $idea->status)) }}
                            </span>
                        @endif
                    </div>

                    <div class="flex-shrink-0">
                        @if ($editingIdeaId === $idea->id)
                            <button wire:click="saveIdea({{ $idea->id }})" class="text-green-600 hover:text-green-900 text-sm font-medium">Save</button>
                            <button wire:click="cancelEdit" class="text-gray-600 hover:text-gray-900 ml-2 text-sm font-medium">Cancel</button>
                        @else
                            @php
                                $user = auth()->user();
                                $team = $user->currentTeam;

                                $canEditAnything = $user->is_admin ||
                                                   $user->id === $idea->user_id ||
                                                   ($team && $user->hasTeamPermission($team, 'update-yellow')) ||
                                                   ($team && $user->hasTeamPermission($team, 'update-red'));

                                $canDelete = $user->is_admin || $user->id === $idea->user_id;
                            @endphp

                            @if ($canEditAnything)
                                <button wire:click="editIdea({{ $idea->id }})" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</button>
                            @endif

                            @if ($canDelete)
                                <button
                                    wire:click="deleteIdea({{ $idea->id }})"
                                    wire:confirm="Are you sure you want to delete this idea?"
                                    class="text-red-600 hover:text-red-900 ml-2 text-sm font-medium">
                                    Delete
                                </button>
                            @endif
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    @if ($editingIdeaId === $idea->id && (auth()->user()->is_admin || auth()->user()->id === $idea->user_id))
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Problem</label>
                            <input type="text" wire:model="problem_short" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                            @error('problem_short') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <a href="{{ route('idea.detail', $idea) }}" class="text-lg font-bold text-indigo-600 hover:text-indigo-900">
                            {{ $idea->problem_short }}
                        </a>
                    @endif

                    @if ($editingIdeaId === $idea->id && (auth()->user()->is_admin || auth()->user()->id === $idea->user_id))
                         <div class="mt-2">
                            <label class="block text-xs font-medium text-gray-700">Details</label>
                            <textarea wire:model="problem_detail" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" rows="3"></textarea>
                            @error('problem_detail') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    @else
                        <p class="text-sm text-gray-600">{{ $idea->problem_detail }}</p>
                    @endif
                </div>

                @if ($editingIdeaId === $idea->id)
                    <div class="border-t pt-4 mt-4 grid grid-cols-2 gap-4">

                        @if (auth()->user()->is_admin || auth()->user()->id === $idea->user_id)
                            <div class="col-span-2 space-y-2 p-2 bg-gray-50 rounded-md">
                                <h4 class="font-medium text-sm text-gray-800">Core Details</h4>
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Goal</label>
                                    <textarea wire:model="goal" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" rows="3"></textarea>
                                    @error('goal') <span class="block text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif

                        <div class="space-y-2 p-2 bg-yellow-50 rounded-md">
                            <h4 class="font-medium text-sm text-yellow-800">Prioritization</h4>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Schmerz</label>
                                <input type="number" wire:model="schmerz" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Prio 1</label>
                                <input type="text" wire:model="prio_1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Prio 2</label>
                                <input type="text" wire:model="prio_2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Umsetzung</label>
                                <input type="number" wire:model="umsetzung" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-yellow'))>
                            </div>
                        </div>
                        <div class="space-y-2 p-2 bg-red-50 rounded-md">
                            <h4 class="font-medium text-sm text-red-800">Development</h4>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Kosten</label>
                                <input type="text" wire:model="kosten" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-red'))>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Dauer</label>
                                <input type="number" wire:model="dauer" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-red'))>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs font-medium text-gray-700">Lösung</label>
                            <textarea wire:model="loesung" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm" @disabled(!auth()->user()->is_admin && !auth()->user()->hasTeamPermission(auth()->user()->currentTeam, 'update-red'))></textarea>
                        </div>
                    </div>
                @else
                    <div class="border-t pt-4 mt-4 grid grid-cols-2 gap-x-4 gap-y-2 text-sm">
                        <div class="font-medium text-gray-500">Schmerz:</div>
                        <div class="font-bold text-gray-900">{{ $idea->schmerz ?? '---' }}</div>
                        <div class="font-medium text-gray-500">Kosten:</div>
                        <div class="text-gray-900">${{ number_format($idea->kosten, 2) ?? '---' }}</div>
                        <div class="font-medium text-gray-500">Dauer:</div>
                        <div class="text-gray-900">{{ $idea->dauer ?? '---' }} days</div>
                        <div class="font-medium text-gray-500">Umsetzung:</div>
                        <div class="font-bold text-gray-900">{{ $idea->umsetzung ?? '---' }}</div>
                        <div class="col-span-2 font-medium text-gray-500">Lösung:</div>
                        <div class="col-span-2 text-sm text-gray-700">
                            {{ $idea->loesung ?? '---' }}
                        </div>
                    </div>
                @endif

            </div>
        @empty
            <div class="text-center p-12">
                <h3 class="text-lg font-medium text-gray-700">No Ideas Found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    Your filters returned no results.
                </p>
            </div>
        @endforelse

        <div class="p-4">
            {{ $ideas->links() }}
        </div>
    </div>
</div>
