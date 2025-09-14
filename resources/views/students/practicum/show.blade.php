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
                        </div>
                        <div class="mt-6 border-t pt-6">
                            <h4 class="text-base font-medium text-gray-900 mb-4">
                                {{ __('My Final Scores') }}
                            </h4>

                            @if ($myEnrollment && $myEnrollment->final_score !== null)
                                <dl class="space-y-3 text-sm">
                                    <div class="flex justify-between">
                                        <dt class="text-gray-500">{{ __('Final Active Score') }}</dt>
                                        <dd class="text-gray-900 font-medium">
                                            {{ number_format($myEnrollment->final_active_score, 2) }}</dd>
                                    </div>
                                    <div class="flex justify-between">
                                        <dt class="text-gray-500">{{ __('Final Report Score') }}</dt>
                                        <dd class="text-gray-900 font-medium">
                                            {{ number_format($myEnrollment->final_report_score, 2) }}</dd>
                                    </div>
                                    <div class="flex justify-between text-base mt-3 pt-3 border-t">
                                        <dt class="font-semibold text-gray-800">{{ __('TOTAL FINAL SCORE') }}</dt>
                                        <dd class="font-bold text-indigo-600">
                                            {{ number_format($myEnrollment->final_score, 2) }}</dd>
                                    </div>
                                    <div class="flex justify-between items-center text-base">
                                        <dt class="font-semibold text-gray-800">{{ __('FINAL GRADE') }}</dt>
                                        <dd>
                                            @if ($myEnrollment->final_grade == 'A')
                                                <span
                                                    class="px-3 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">A</span>
                                            @elseif (in_array($myEnrollment->final_grade, ['B', 'C']))
                                                <span
                                                    class="px-3 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">{{ $myEnrollment->final_grade }}</span>
                                            @else
                                                <span
                                                    class="px-3 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">{{ $myEnrollment->final_grade ?? 'E' }}</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            @else
                                <p class="text-sm text-gray-500 italic">
                                    {{ __('Final scores have not been calculated yet.') }}
                                </p>
                            @endif
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
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ activeTab: 'schedule' }">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-6 overflow-x-auto px-6" aria-label="Tabs">
                                <button x-on:click="activeTab = 'schedule'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'schedule' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Schedule') }}
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
                                {{-- <button x-on:click="activeTab = 'module'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'module' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    {{ __('Modules') }}
                                </button> --}}
                            </nav>
                        </div>

                        <div class="p-6">
                            <div x-show="activeTab === 'schedule'">
                                @include('students.practicum.partials.schedule', [
                                    'practicum' => $practicum,
                                    'myAttendances' => $myAttendances,
                                ])
                            </div>

                            {{-- <div x-show="activeTab === 'announcement'">
                                <h3 class="text-lg">{{ __('Announcements') }}</h3>
                            </div> --}}

                            <div x-show="activeTab === 'assignment'">
                                @include('students.practicum.partials.assignments', [
                                    'practicum' => $practicum,
                                    'myEnrollment' => $myEnrollment,
                                ])
                            </div>

                            {{-- <div x-show="activeTab === 'module'">
                                <h3 class="text-lg">{{ __('Modules & Resources Content') }}</h3>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('students.practicum.partials.submit-assignment-modal')

    @include('students.practicum.partials.view-journal-modal')
</x-app-layout>
