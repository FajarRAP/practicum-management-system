<x-app-layout>
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Meeting Journal') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $schedule->practicum->course->name }} - {{ __('Meeting') }} {{ $schedule->meeting_number }}
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('attendance.store') }}">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                {{-- Assistant Attendance --}}
                @can('manage_assistant_attendance')
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ __('Assistant Attendance') }}</h3>
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th class="py-3 px-6 w-3/4">{{ __('Assistant Name') }}</th>
                                            <th class="py-3 px-6">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($assistants as $assistant)
                                            @php
                                                $assistantAttendance = $assistantAttendances->get($assistant->id);
                                            @endphp
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <th scope="row" class="py-4 px-6 font-medium text-gray-900">
                                                    {{ $assistant->name }}
                                                </th>
                                                <td class="py-4 px-6">
                                                    <select name="assistant_attendances[{{ $assistant->id }}]"
                                                        class="text-xs border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                        @foreach (['PRESENT', 'EXCUSED', 'SICK', 'ABSENT'] as $status)
                                                            <option value="{{ $status }}"
                                                                {{ ($assistantAttendance->status ?? null) == $status ? 'selected' : '' }}>
                                                                {{ $status }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="2" class="py-4 px-6 text-center italic text-gray-500">
                                                    {{ __('No assistants assigned to this practicum.') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endcan

                {{-- Student Attendance --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b">
                        <h3 class="font-semibold">{{ $schedule->topic }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($schedule->date)->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="py-3 px-4">{{ __('Student') }}</th>
                                    <th class="py-3 px-4">{{ __('Attendance') }}</th>
                                    <th class="py-3 px-2 text-center">{{ __('Participation') }}</th>
                                    <th class="py-3 px-2 text-center">{{ __('Creativity') }}</th>
                                    <th class="py-3 px-2 text-center">{{ __('Report') }}</th>
                                    <th class="py-3 px-2 text-center">{{ __('Active') }}</th>
                                    <th class="py-3 px-2 text-center">{{ __('Module') }}</th>
                                    <th class="py-3 px-4 text-center">{{ __('Assignment Submission') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($practicum->enrollments as $enrollment)
                                    @php
                                        $attendance = $attendances->get($enrollment->user_id);
                                    @endphp
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <th scope="row" class="py-4 px-4 font-medium text-gray-900">
                                            <div>{{ $enrollment->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $enrollment->user->identity_number }}
                                            </div>
                                        </th>
                                        <td class="py-4 px-4">
                                            <select name="attendances[{{ $enrollment->user_id }}]"
                                                class="text-xs border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                                @foreach (['PRESENT', 'EXCUSED', 'SICK', 'ABSENT'] as $status)
                                                    <option value="{{ $status }}"
                                                        {{ ($attendance->status ?? null) == $status ? 'selected' : '' }}>
                                                        {{ $status }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-4 px-2 text-center">
                                            <x-text-input type="number"
                                                name="scores[{{ $enrollment->user_id }}][participation_score]"
                                                class="w-20 text-sm" min="0" max="100"
                                                value="{{ $attendance->participation_score ?? '' }}" />
                                        </td>
                                        <td class="py-4 px-2 text-center">
                                            <x-text-input type="number"
                                                name="scores[{{ $enrollment->user_id }}][creativity_score]"
                                                class="w-20 text-sm inline-flex" min="0" max="100"
                                                value="{{ $attendance->creativity_score ?? '' }}" />
                                        </td>
                                        <td class="py-4 px-2 text-center">
                                            <x-text-input type="number"
                                                name="scores[{{ $enrollment->user_id }}][report_score]"
                                                class="w-20 text-sm" min="0" max="100"
                                                value="{{ $attendance->report_score ?? '' }}" />
                                        </td>
                                        <td class="py-4 px-2 text-center">
                                            <x-text-input type="number"
                                                name="scores[{{ $enrollment->user_id }}][active_score]"
                                                class="w-20 text-sm" min="0" max="100"
                                                value="{{ $attendance->active_score ?? '' }}" />
                                        </td>
                                        <td class="py-4 px-2 text-center">
                                            <x-text-input type="number"
                                                name="scores[{{ $enrollment->user_id }}][module_score]"
                                                class="w-20 text-sm" min="0" max="100"
                                                value="{{ $attendance->module_score ?? '' }}" />
                                        </td>
                                        <td class="py-4 px-4 text-center">
                                            @if ($schedule->assignment)
                                                @php
                                                    $submission = $submissions->get($enrollment->user_id);
                                                @endphp

                                                @if ($submission)
                                                    <a href="{{ Storage::url($submission->file_path) }}"
                                                        target="_blank"
                                                        class="block font-medium text-blue-600 hover:underline">
                                                        @if ($submission->is_late)
                                                            <span
                                                                class="text-yellow-600">{{ __('Submitted Late') }}</span>
                                                        @else
                                                            <span class="text-green-600">{{ __('Submitted') }}</span>
                                                        @endif
                                                    </a>
                                                    <span
                                                        class="text-xs text-gray-500">{{ $submission->created_at->isoFormat('D MMM, HH:mm') }}</span>
                                                @else
                                                    <span
                                                        class="font-medium text-red-600">{{ __('Not Submitted') }}</span>
                                                @endif
                                            @else
                                                <span
                                                    class="text-xs text-gray-400 italic">{{ __('No Assignment') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="py-4 px-6 text-center">{{ __('No students data.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-6 flex justify-end">
                    <x-primary-button>{{ __('Save All Records') }}</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
