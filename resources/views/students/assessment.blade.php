<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            <a href="{{ route('assessment.final-score') }}" class="self-end">
                <x-primary-button>
                    {{ __('Final Score') }}
                </x-primary-button>
            </a>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 border-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Course') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Activity') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Assessment') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcements as $announcement)
                                @php
                                    $assessment = request()
                                        ->user()
                                        ->assessments->where('announcement_id', $announcement->id)
                                        ->first();
                                    $attendance = request()
                                        ->user()
                                        ->attendances->where('announcement_id', $announcement->id)
                                        ->first();
                                    $submission = null;
                                    request()
                                        ->user()
                                        ->submissions->each(function ($submissionItem) use (
                                            $announcement,
                                            &$submission,
                                        ) {
                                            if ($submissionItem->assignment->announcement_id === $announcement->id) {
                                                $submission = $submissionItem;
                                            }
                                        });
                                @endphp
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $announcement->schedule->course->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $announcement->activity }}
                                    <td class="px-6 py-4">
                                        <a href=""
                                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            x-data
                                            @click.prevent="$dispatch('open-modal', 'view-grade-{{ $announcement->id }}')">
                                            {{ __('View') }}
                                        </a>
                                        <x-modal maxWidth="3xl" name="view-grade-{{ $announcement->id }}" focusable>
                                            <div class="p-6 space-y-4">
                                                <h2 class="text-lg font-medium text-gray-900">
                                                    {{ __('Assessment Details') }}
                                                </h2>
                                                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                                                    <div
                                                        class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                                                        <table
                                                            class="w-full text-sm text-left rtl:text-right text-gray-500 border-gray-300">
                                                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                                                <tr>
                                                                    <th scope="col" class="px-6 py-3">
                                                                        {{ __('Attendance Status') }}
                                                                    </th>
                                                                    <th scope="col" class="px-6 py-3">
                                                                        {{ __('Attendance Score') }}
                                                                    </th>
                                                                    <th scope="col" class="px-6 py-3">
                                                                        {{ __('Participation Score') }}
                                                                    </th>
                                                                    <th scope="col" class="px-6 py-3">
                                                                        {{ __('Creativity Score') }}
                                                                    </th>
                                                                    @if ($announcement->is_schedule_announcement)
                                                                        <th scope="col" class="px-6 py-3">
                                                                            {{ __('Report Score') }}
                                                                        </th>
                                                                        <th scope="col" class="px-6 py-3">
                                                                            {{ __('Submisssion File') }}
                                                                        </th>
                                                                    @endif
                                                                    <th scope="col" class="px-6 py-3">
                                                                        {{ __('Active Score') }}
                                                                    </th>
                                                                    <th scope="col" class="px-6 py-3">
                                                                        {{ __('Module Score') }}
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr
                                                                    class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                                                    <td class="px-6 py-4">
                                                                        {{ $attendance?->status }}
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        {{ $assessment?->attendance_score }}
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        {{ $assessment?->participation_score }}
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        {{ $assessment?->creativity_score }}
                                                                    </td>
                                                                    @if ($announcement?->is_schedule_announcement)
                                                                        <td class="px-6 py-4">
                                                                            {{ $assessment?->report_score }}
                                                                        </td>
                                                                        <td class="px-6 py-4">
                                                                            @if ($submission?->file_path)
                                                                                <a href="{{ "../storage/{$submission?->file_path}" }} "
                                                                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                                    {{ __('View') }}
                                                                                </a>
                                                                            @endif
                                                                        </td>
                                                                    @endif
                                                                    <td class="px-6 py-4">
                                                                        {{ $assessment?->active_score }}
                                                                    </td>
                                                                    <td class="px-6 py-4">
                                                                        {{ $assessment?->module_score }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="flex justify-end">
                                                    <x-primary-button @click="$dispatch('close')">
                                                        {{ __('OK') }}
                                                    </x-primary-button>
                                                </div>
                                            </div>
                                        </x-modal>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
