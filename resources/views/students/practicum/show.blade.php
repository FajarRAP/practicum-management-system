<x-app-layout x-data="{ assignment: {}, myJournal: {}, action: '' }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Practicum Details: ') . $practicum->course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            {{ __('Practicum Information') }}
                        </h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <dt class="font-medium text-gray-500">{{ __('Course') }}</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->course->name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">{{ __('Academic Year') }}</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->academicYear->year }} -
                                    {{ $practicum->academicYear->semester }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">{{ __('Shift') }}</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->shift->name }}</dd>
                            </div>
                            {{-- [BARU] Menampilkan informasi Dosen & Asisten --}}
                            {{-- <div>
                                <dt class="font-medium text-gray-500">Lecturer</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->lecturer->name ?? 'Not assigned' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Assistants</dt>
                                <dd class="mt-1 text-gray-900">
                                    @forelse($practicum->assistants as $assistant)
                                        - {{ $assistant->name }} <br>
                                    @empty
                                        Not assigned
                                    @endforelse
                                </dd>
                            </div> --}}
                        </div>
                        <div class="mt-6 border-t pt-6">
                            <a href="{{ route('my-practicum.index') }}"
                                class="w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                {{ __('Back to My Practicums') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ activeTab: 'jadwal' }">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-6 overflow-x-auto px-6" aria-label="Tabs">
                                <button @click="activeTab = 'jadwal'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'jadwal' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Schedule') }}
                                </button>
                                <button @click="activeTab = 'pengumuman'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'pengumuman' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Announcements') }}
                                </button>
                                <button @click="activeTab = 'tugas'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'tugas' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Assignments') }}
                                </button>
                                <button @click="activeTab = 'materi'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'materi' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Modules') }}
                                </button>
                            </nav>
                        </div>

                        <div class="p-6">
                            {{-- Tab Jadwal --}}
                            <div x-show="activeTab === 'jadwal'">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">
                                    {{ __('Practicum Schedule & Journal') }}</h3>
                                <div class="overflow-x-auto relative">
                                    <table class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr>
                                                <th class="py-3 px-6">{{ __('Meeting') }}</th>
                                                <th class="py-3 px-6">{{ __('Topic') }}</th>
                                                <th class="py-3 px-6">{{ __('Date & Time') }}</th>
                                                <th class="py-3 px-6 text-center">{{ __('My Journal') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($practicum->schedules->sortBy('meeting_number') as $schedule)
                                                <tr class="bg-white border-b hover:bg-gray-50">
                                                    <td class="py-4 px-6 font-medium">{{ $schedule->meeting_number }}
                                                    </td>
                                                    <td class="py-4 px-6">{{ $schedule->topic }}</td>
                                                    <td class="py-4 px-6">
                                                        {{ \Carbon\Carbon::parse($schedule->date)->isoFormat('D MMM Y') }},
                                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                    </td>
                                                    <td class="py-4 px-6 text-center">

                                                        @php
                                                            $attendance = $myAttendances->get($schedule->id);
                                                        @endphp

                                                        @if ($attendance)
                                                            <button
                                                                class="font-medium text-indigo-600 hover:underline text-sm"
                                                                x-on:click.prevent="myJournal = {{ json_encode($attendance) }};
                                                                $dispatch('open-modal', 'view-journal-modal');">
                                                                {{ __('View Details') }}
                                                            </button>
                                                        @else
                                                            <span
                                                                class="text-xs text-gray-400 italic">{{ __('No record') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-4 px-6 text-center">
                                                        {{ __('No schedule available yet.') }}</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Tab Pengumuman --}}
                            <div x-show="activeTab === 'pengumuman'">
                                <h3 class="text-lg">{{ __('Announcements') }}</h3>
                            </div>

                            {{-- Tab Tugas --}}
                            <div x-show="activeTab === 'tugas'">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">{{ __('Assignment List') }}</h3>
                                </div>
                                <div class="space-y-4">
                                    @forelse ($practicum->assignments as $assignment)
                                        <div class="p-4 bg-white border rounded-lg flex justify-between items-center">
                                            <div>
                                                <h4 class="font-semibold">{{ $assignment->title }}</h4>
                                                <p class="text-sm text-gray-600 mt-1">{{ $assignment->description }}
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

                            {{-- Tab Materi --}}
                            <div x-show="activeTab === 'materi'">
                                <h3 class="text-lg">{{ __('Modules & Resources Content') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('students.practicum.partials.submit-assignment-modal')

    @include('students.practicum.partials.view-journal-modal')
</x-app-layout>
