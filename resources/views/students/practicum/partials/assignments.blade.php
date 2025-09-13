<div class="flex flex-col gap-4">
    <h3 class="text-lg font-medium text-gray-900">{{ __('Assignment List') }}</h3>

    <div class="space-y-4">
        @forelse ($practicum->assignments as $assignment)
            <div class="p-4 bg-white border rounded-lg flex justify-between items-center">
                <div>
                    <h4 class="font-semibold">{{ $assignment->title }}</h4>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ $assignment->description }}
                    </p>
                    <p class="text-xs text-red-600 mt-2 font-medium">
                        {{ __('Deadline:') }}
                        {{ \Carbon\Carbon::parse($assignment->deadline)->isoFormat('dddd, D MMMM Y, HH:mm') }}
                    </p>
                </div>
                <div>
                    @php
                        $submission = $mySubmissions[$assignment->id] ?? null;
                    @endphp

                    @if ($submission)
                        <div class="text-center">
                            @if ($submission->is_late)
                                <span
                                    class="px-3 py-1.5 font-semibold leading-tight text-yellow-800 bg-yellow-100 rounded-full text-xs">
                                    {{ __('Submitted Late') }}
                                </span>
                            @else
                                <span
                                    class="px-3 py-1.5 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">{{ __('Submitted') }}</span>
                            @endif
                            <a href="{{ Storage::url($submission->file_path) }}"
                                class="mt-2 text-xs text-blue-600 hover:underline">{{ __('View Submission') }}</a>
                        </div>
                    @else
                        <x-primary-button
                            x-on:click.prevent="assignment = {{ json_encode($assignment) }};
                            action = '{{ route('assignment-submission.store', [$practicum, $assignment]) }}';
                            $dispatch('open-modal', 'submit-assignment-modal');">
                            {{ __('Submit Assignment') }}</x-primary-button>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-8">
                {{ __('No assignments have been posted yet.') }}
            </div>
        @endforelse
    </div>
</div>
