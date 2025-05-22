<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessment') . ' ' . $announcement->schedule->course->name . ' - ' . $announcement->activity }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form id="assessment" method="POST" action="{{ route('assessment.store', $announcement) }}"
                class="flex flex-col gap-4">
                @csrf
                @method('POST')
                @hasrole('assistant')
                    <x-primary-button class="self-end" x-data @click.prevent="$dispatch('open-modal', 'add-assessment')">
                        {{ __('Save') }}
                    </x-primary-button>
                @endhasrole

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                    <div
                        class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 border-gray-300">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Name') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Student Number') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Attendance') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Participation') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Activeness') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Report') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Submisssion File') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($submissions as $submission)
                                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            {{ $submission->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $submission->student_number }}
                                        <td class="px-6 py-4">
                                            {{ Str::of($submission->status)->lower()->ucfirst() }}
                                        </td>
                                        @hasrole('assistant')
                                            <td class="px-6 py-4">
                                                <x-text-input type="number"
                                                    name="assessments[{{ $submission->user_id }}][participation]"
                                                    :value="$submission->participation_score" />
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-text-input type="number"
                                                    name="assessments[{{ $submission->user_id }}][activeness]"
                                                    :value="$submission->active_score" />
                                            </td>
                                            <td class="px-6 py-4">
                                                <x-text-input type="number"
                                                    name="assessments[{{ $submission->user_id }}][report]"
                                                    :value="$submission->report_score" />
                                            </td>
                                        @else
                                            <td class="px-6 py-4">
                                                {{ $submission->participation_score }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $submission->active_score }}
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $submission->report_score }}
                                            </td>
                                        @endhasrole
                                        <td class="px-6 py-4">
                                            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                href="{{ asset("storage/$submission->file_path") }}">
                                                {{ __('View') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @hasrole('assistant')
        <x-modal name="add-assessment" :show="$errors->addAnnouncement->isNotEmpty()" focusable>
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Save This Assessment') }}
                </h2>

                <div class="flex justify-end">
                    <x-secondary-button @click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3" @click="document.querySelector('#assessment').submit()">
                        {{ __('Save') }}
                    </x-primary-button>
                </div>
            </div>
        </x-modal>
    @endhasrole
</x-app-layout>
