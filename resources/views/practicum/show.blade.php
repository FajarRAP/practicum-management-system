<x-app-layout x-data="{ editSchedule: {}, editAssignment: {}, action: '' }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Practicum Detail: ') . $practicum->course->name }}
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
                                <dt class="font-medium text-gray-500">{{ __('Status') }}</dt>
                                <dd class="mt-1">
                                    @if ($practicum->academicYear->status == 'ACTIVE')
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">{{ __('Active') }}</span>
                                    @else
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full text-xs">{{ __('Inactive') }}</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">{{ __('Number of Participants') }}</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->enrollments->count() }}</dd>
                            </div>
                        </div>
                        <div class="mt-6 border-t pt-6">
                            <a href="{{ route('practicum.index') }}"
                                class="w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                {{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ activeTab: 'participant' }">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-6 overflow-x-auto px-6" aria-label="Tabs">
                                <button x-on:click="activeTab = 'participant'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'participant' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Participants') }}
                                </button>
                                <button x-on:click="activeTab = 'schedule'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'schedule' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Schedule & Attendance') }}
                                </button>
                                {{-- <button x-on:click="activeTab = 'announcement'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'announcement' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Announcements') }}
                                </button> --}}
                                <button x-on:click="activeTab = 'assignment'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'assignment' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Assignments') }}
                                </button>
                            </nav>
                        </div>

                        <div class="p-6">
                            <div x-show="activeTab === 'participant'">
                                @include('practicum.partials.enrollments', ['practicum' => $practicum])
                            </div>

                            <div x-show="activeTab === 'schedule'">
                                @include('practicum.partials.schedule', ['practicum' => $practicum])
                            </div>

                            {{-- <div x-show="activeTab === 'announcement'">
                                <h3 class="text-lg">Manajemen Pengumuman</h3>
                            </div> --}}

                            <div x-show="activeTab === 'assignment'">
                                @include('practicum.partials.assignments', ['practicum' => $practicum])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Student Status Modal --}}
            {{-- <x-modal name="reject-enrollment" focusable>
                <form method="POST" x-bind:action="action" class="p-6">
                    @csrf
                    @method('PATCH')

                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Reject Enrollment') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Please provide a reason for rejecting this enrollment. The student will be notified.') }}
                    </p>

                    <div class="mt-6">
                        <x-input-label for="rejection_reason" value="{{ __('Reason') }}" class="sr-only" />
                        <x-textarea id="rejection_reason" name="rejection_reason" class="mt-1 block w-full"
                            placeholder="{{ __('Reason for rejection...') }}" required></x-textarea>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-danger-button class="ms-3">
                            {{ __('Reject Enrollment') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal> --}}

        </div>
    </div>
    @can('schedule.add')
        @include('practicum.partials.add-schedule-modal', ['practicum' => $practicum])
    @endcan
    @can('schdeule.edit')
        @include('practicum.partials.edit-schedule-modal', ['practicum' => $practicum])
    @endcan
    @can('assignment.add')
        @include('practicum.partials.add-assignment-modal', ['practicum' => $practicum])
    @endcan
    @can('assignment.edit')
        @include('practicum.partials.edit-assignment-modal', ['practicum' => $practicum])
    @endcan
    @can('schedule.approve')
        @include('practicum.partials.reject-schedule-modal')
    @endcan
</x-app-layout>
