<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Active Practicums') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($myPracticums as $practicum)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col justify-between">
                        <div>
                            <div class="text-xs text-gray-500">{{ $practicum->academicYear->year }} -
                                {{ $practicum->academicYear->semester }}</div>
                            <h3 class="font-semibold text-lg text-gray-900 mt-1">{{ $practicum->course->name }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $practicum->shift->name }}</p>
                        </div>
                        <a href="{{ route('practicum.show', $practicum) }}"
                            class="mt-4 inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500">
                            {{ __('Enter Class') }}
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-12">
                        {{ __('You are not enrolled in any active practicum.') }}
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
