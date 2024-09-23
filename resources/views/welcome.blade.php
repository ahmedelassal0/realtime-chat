<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @foreach($users as $user)
                <a href="{{ route('user.chat', $user) }}">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg my-4">
                        <div class="p-6 text-gray-900">
                            {{ $user->name }}
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</x-app-layout>
