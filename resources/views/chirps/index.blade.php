<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Chirps') }}
        </h2>
    </x-slot>

    <div style="margin-left: 20px; margin-top: 20px;">
        <h1><strong>Welcome to my travel blog</strong></h1>
        <h1><strong><u>Reykjavik - The Charming Capital</u></strong></h1>
        <p>Our Icelandic adventure begins in Reykjavik, the vibrant capital city. The colorful buildings, lively streets, and friendly locals instantly make you feel at home.<br>We explore the iconic Hallgrimskirkja church, take a stroll along the picturesque waterfront, and indulge in delicious Icelandic cuisine at a local restaurant.<br> The city's charm leaves us eager for what lies ahead.</p>
    </div>

    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form method="POST" action="{{ route('chirps.store') }}">
            @csrf
            <textarea
                name="message"
                placeholder="{{ __('What\'s on your mind?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('Chirp') }}</x-primary-button>
        </form>

        <div class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @foreach ($chirps as $chirp)
                <div class="p-6 flex space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-600 -scale-x-100" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <div class="flex-1">
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-gray-800">{{ $chirp->user->name }}</span>
                                <span class="text-gray-600">{{ $chirp->created_at->diffForHumans() }}</span>
                                @unless ($chirp->created_at->eq($chirp->updated_at))
                                    <small class="text-sm text-gray-600"> &middot; {{ __('edited') }}</small>
                                @endunless
                            </div>
                            @if ($chirp->user->is(auth()->user()))
                                <x-dropdown>
                                    <x-slot name="trigger">
                                        <button>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z" />
                                            </svg>
                                        </button>
                                    </x-slot>
                                    <x-slot name="content">
                                        <x-dropdown-link :href="route('chirps.edit', $chirp)">
                                            {{ __('Edit') }}
                                        </x-dropdown-link>
                                        <x-dropdown-link :href="route('chirps.destroy', $chirp)" onclick="event.preventDefault(); document.getElementById('delete-form-{{ $chirp->id }}').submit();">
                                            {{ __('Delete') }}
                                        </x-dropdown-link>
                                        <form id="delete-form-{{ $chirp->id }}" action="{{ route('chirps.destroy', $chirp) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                    </x-slot>
                                </x-dropdown>
                            @endif
                        </div>
                        <p class="mt-4 text-lg text-gray-900">{{ $chirp->message }}</p>

                        <!-- Kommentaaride vorm -->
                        <form method="POST" action="{{ route('chirps.comments.store', $chirp) }}">
                            @csrf
                            <input type="hidden" name="chirp_id" value="{{ $chirp->id }}">
                            <textarea name="content" placeholder="Enter your comment" rows="3"></textarea>
                            <button type="submit">Post Comment</button>
                        </form>
                        
                        <!-- Kommentaaride kuvamine -->
                        @if ($chirp->comments && $chirp->comments->count() > 0)
                            @foreach ($chirp->comments as $comment)
                                <div class="p-2 mt-2 bg-gray-100 rounded-lg">
                                    <p class="text-sm text-gray-800">{{ $comment->content }}</p>
                                    <p class="text-xs text-gray-600">{{ $comment->created_at->format('j M Y, g:i a') }}</p>
                                    @if (auth()->user() && auth()->user()->isAdmin())
                                        <form action="{{ route('comments.destroy', $comment) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

