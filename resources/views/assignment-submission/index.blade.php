<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('View Submissions') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $assignment->title }}
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- action="{{ route('assignment-submission.storeScores', $assignment) }}" --}}
            {{-- <form method="POST"> --}}
            @csrf
            @method('PATCH')

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b">
                    <h3 class="font-semibold">{{ $assignment->practicum->course->name }}</h3>
                    <p class="text-sm text-gray-600">
                        {{ __('Deadline') }}:
                        {{ \Carbon\Carbon::parse($assignment->deadline)->isoFormat('dddd, D MMMM Y, HH:mm') }}
                    </p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="py-3 px-6">{{ __('Student Name') }}</th>
                                <th class="py-3 px-6">{{ __('Submission Status') }}</th>
                                {{-- <th class="py-3 px-6 w-1/6">{{ __('Score (0-100)') }}</th> --}}
                                {{-- <th class="py-3 px-6 w-1/3">{{ __('Feedback') }}</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                @php
                                    $submission = $submissions->get($enrollment->user_id);
                                @endphp

                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900">
                                        <div>{{ $enrollment->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $enrollment->user->identity_number }}
                                        </div>
                                    </th>
                                    <td class="py-4 px-6">
                                        @if ($submission)
                                            <a href="{{ Storage::url($submission->file_path) }}" target="_blank"
                                                class="block font-medium text-blue-600 hover:underline">
                                                @if ($submission->is_late)
                                                    <span class="text-yellow-600">{{ __('Submitted Late') }}</span>
                                                @else
                                                    <span class="text-green-600">{{ __('Submitted') }}</span>
                                                @endif
                                            </a>
                                            <span
                                                class="text-xs text-gray-500">{{ $submission->created_at->isoFormat('D MMM Y, HH:mm') }}</span>
                                        @else
                                            <span class="font-medium text-red-600">{{ __('Not Submitted') }}</span>
                                        @endif
                                    </td>
                                    {{-- <td class="py-4 px-6">
                                            @if ($submission)
                                                <x-text-input type="number" name="scores[{{ $submission->id }}]"
                                                    class="w-full text-sm" min="0" max="100" step="0.5"
                                                    value="{{ $submission->score ?? '' }}" />
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td> --}}
                                    {{-- <td class="py-4 px-6">
                                            @if ($submission)
                                                <x-textarea name="feedback[{{ $submission->id }}]" rows="2"
                                                    class="w-full text-sm"
                                                    placeholder="Provide feedback...">{{ $submission->feedback ?? '' }}</x-textarea>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-4 px-6 text-center">
                                        {{ __('There are no students in this practicum.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- <div class="mt-4 flex justify-end">
                <x-primary-button>
                    {{ __('Save Scores & Feedback') }}
                </x-primary-button>
            </div> --}}
            {{-- </form> --}}
        </div>
    </div>
</x-app-layout>
