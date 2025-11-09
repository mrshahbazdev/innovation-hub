<div>
    {{-- Page Header --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Idea: {{ $idea->problem_short }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h3 class="text-2xl font-bold text-gray-900">{{ $idea->problem_short }}</h3>

                    <span class="mt-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                        @if($idea->status == 'new') bg-blue-100 text-blue-800 @endif
                        @if($idea->fistatus == 'pending_review') bg-yellow-100 text-yellow-800 @endif
                        @if($idea->status == 'pending_pricing') bg-purple-100 text-purple-800 @endif
                        @if($idea->status == 'approved') bg-green-100 text-green-800 @endif
                        @if($idea->status == 'rejected') bg-red-100 text-red-800 @endif
                    ">
                        {{ ucfirst(str_replace('_', ' ', $idea->status)) }}
                    </span>

                    <div class="mt-4 border-t pt-4">
                        <h4 class="text-lg font-semibold text-gray-800">Goal</h4>
                        <p class="text-gray-600 mt-1">{{ $idea->goal }}</p>
                    </div>

                    <div class="mt-4 border-t pt-4">
                        <h4 class="text-lg font-semibold text-gray-800">Detailed Problem</h4>
                        <p class="text-gray-600 mt-1">{{ $idea->problem_detail }}</p>
                    </div>

                    <div class="mt-4 border-t pt-4">
                        <h4 class="text-lg font-semibold text-gray-800">Solution (Lösung)</h4>
                        <p class="text-gray-600 mt-1">{{ $idea->loesung ?? 'Not defined yet.' }}</p>
                    </div>
                </div>
            </div>

            <div class="space-y-6">

                <div class="bg-white shadow-lg rounded-lg p-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Details</h4>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Schmerz (Pain)</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $idea->schmerz ?? '---' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Kosten (Cost)</dt>
                            <dd class="text-sm text-gray-900">${{ number_format($idea->kosten, 2) ?? '---' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Dauer (Days)</dt>
                            <dd class="text-sm text-gray-900">{{ $idea->dauer ?? '---' }} days</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm font-medium text-gray-500">Priority (Umsetzung)</dt>
                            <dd class="text-sm font-bold text-gray-900">{{ $idea->umsetzung ?? '---' }}</dd>
                        </div>
                    </dl>
                </div>

                <div x-data="{ tab: 'comments' }" class="bg-white shadow-lg rounded-lg overflow-hidden">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex">
                            <button @click="tab = 'comments'"
                                    :class="tab === 'comments' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                                Comments ({{ $idea->comments->count() }})
                            </button>
                            <button @click="tab = 'activity'"
                                    :class="tab === 'activity' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="w-1/2 py-4 px-1 text-center border-b-2 font-medium text-sm">
                                Activity
                            </button>
                        </nav>
                    </div>

                    <div class="p-6">
                        <div x-show="tab === 'comments'">
                            <form wire:submit="addComment" class="mb-4">
                                <textarea wire:model="newComment" class="block w-full border-gray-300 rounded-md shadow-sm text-sm" rows="3" placeholder="Add a comment..."></textarea>
                                @error('newComment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <div class="text-right mt-2">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                        Post
                                    </button>
                                </div>
                            </form>
                            <div class="space-y-4">
                                @forelse($idea->comments as $comment)
                                    <div class="flex space-x-3" wire:key="comment-{{ $comment->id }}">
                                        <div class="flex-shrink-0">
                                            <img class="h-8 w-8 rounded-full" src="{{ $comment->user->profile_photo_url }}" alt="{{ $comment->user->name }}">
                                        </div>
                                        <div class="flex-1 bg-gray-100 rounded-lg p-3">
                                            <div class="flex justify-between items-center">
                                                <p class="text-sm font-medium text-gray-900">{{ $comment->user->name }}</p>
                                                <time class="text-xs text-gray-500" title="{{ $comment->created_at->format('Y-m-d H:i:s') }}">
                                                    {{ $comment->created_at->diffForHumans() }}
                                                </time>
                                            </div>
                                            <p class="text-sm text-gray-700 mt-1">{{ $comment->body }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 text-center">No comments yet.</p>
                                @endforelse
                            </div>
                        </div>

                        <div x-show="tab === 'activity'" class="flow-root">
                            <ul role="list" class="-mb-8">
                                @forelse($activities as $activity)
                                    <li wire:key="activity-{{ $activity->id }}">
                                        <div class="relative pb-8">
                                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                            <div class="relative flex space-x-3">
                                                <div>
                                                    <span class="h-8 w-8 rounded-full bg-gray-400 flex items-center justify-center ring-8 ring-white">
                                                        <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                          <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.667 0l3.181-3.183m-4.991-4.992v4.992m0 0h-4.992m4.992 0l-3.181-3.183a8.25 8.25 0 00-11.667 0L2.985 14.652z" />
                                                        </svg>
                                                    </span>
                                                </div>
                                                <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                    <div>
                                                        <p class="text-sm text-gray-500">
                                                            <span class="font-medium text-gray-900">{{ $activity->causer->name ?? 'System' }}</span>
                                                            updated the idea.
                                                        </p>

                                                        {{-- Yahan changes dikhayein --}}
                                                        <div class="mt-2 text-sm text-gray-700">
                                                            <ul class="list-disc pl-5 space-y-1">
                                                                @foreach($activity->properties['attributes'] as $key => $value)
                                                                    {{-- Sirf 'old' array se check karein ke value tabdeel hui hai --}}
                                                                    @if(isset($activity->properties['old'][$key]))
                                                                        <li>
                                                                            Changed <strong>{{ $key }}</strong>
                                                                            from
                                                                            <span class="text-red-600 line-through">{{ $activity->properties['old'][$key] ?? 'nothing' }}</span>
                                                                            to
                                                                            <span class="text-green-600">{{ $value }}</span>
                                                                        </li>
                                                                    @endif
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="whitespace-nowrap text-right text-sm text-gray-500">
                                                        <time title="{{ $activity->created_at->format('Y-m-d H:i:s') }}">{{ $activity->created_at->diffForHumans() }}</time>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li>
                                        <p class="text-sm text-gray-500 text-center">No activity recorded yet.</p>
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</div>
