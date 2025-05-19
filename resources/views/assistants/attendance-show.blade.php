<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form id="attendance" method="POST" action="{{ route('attendance.store', $announcement) }}"
                class="flex flex-col gap-4">
                @csrf
                @method('POST')
                <x-primary-button class="self-end" x-data @click.prevent="$dispatch('open-modal', 'add-attendance')">
                    {{ __('Save') }}
                </x-primary-button>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                    <p class="text-lg font-medium m-4 sm:m-6">
                        {{ __('Assistants Attendance') }}
                    </p>
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
                                        {{ __('Description') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assistants as $assistant)
                                    @php
                                        $assistantStatus = $assistant->attendances
                                            ->where('announcement_id', $announcement->id)
                                            ->first()?->status;
                                        $evaluateStatus = fn($status) => $assistantStatus === $status ? 'selected' : '';
                                    @endphp
                                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            {{ $assistant->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $assistant->email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-select-input name="users[{{ $assistant->id }}]">
                                                <x-slot name="options">
                                                    <option value="" disabled
                                                        {{ !$assistantStatus ? 'selected' : '' }}>
                                                        {{ __('Select') }}
                                                    </option>
                                                    <option value="PRESENT" {{ $evaluateStatus('PRESENT') }}>
                                                        {{ __('Present') }}</option>
                                                    <option value="SICK" {{ $evaluateStatus('SICK') }}>
                                                        {{ __('Sick') }}</option>
                                                    <option value="EXCUSED" {{ $evaluateStatus('EXCUSED') }}>
                                                        {{ __('Excused') }}</option>
                                                    <option value="ABSENT" {{ $evaluateStatus('ABSENT') }}>
                                                        {{ __('Absent') }}</option>
                                                </x-slot>
                                            </x-select-input>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                    <p class="text-lg font-medium m-4 sm:m-6">
                        {{ __('Students Attendance') }}
                    </p>
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
                                        {{ __('Description') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($announcement->schedule->enrollments as $enrollment)
                                    @php
                                        $studentStatus = $enrollment->user->attendances
                                            ->where('announcement_id', $announcement->id)
                                            ->first()?->status;
                                        $evaluateStatus = fn($status) => $studentStatus === $status ? 'selected' : '';
                                    @endphp
                                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            {{ $enrollment->user->name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $enrollment->user->email }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-select-input name="users[{{ $enrollment->user->id }}]">
                                                <x-slot name="options">
                                                    <option value="" disabled
                                                        {{ !$studentStatus ? 'selected' : '' }}>
                                                        {{ __('Select') }}
                                                    </option>
                                                    <option value="PRESENT" {{ $evaluateStatus('PRESENT') }}>
                                                        {{ __('Present') }}</option>
                                                    <option value="SICK" {{ $evaluateStatus('SICK') }}>
                                                        {{ __('Sick') }}</option>
                                                    <option value="EXCUSED" {{ $evaluateStatus('EXCUSED') }}>
                                                        {{ __('Excused') }}</option>
                                                    <option value="ABSENT" {{ $evaluateStatus('ABSENT') }}>
                                                        {{ __('Absent') }}</option>
                                                </x-slot>
                                            </x-select-input>
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

    <x-modal name="add-attendance" :show="$errors->addAnnouncement->isNotEmpty()" focusable>
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Save This Attendance') }}
            </h2>

            <div class="flex justify-end">
                <x-secondary-button @click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3" @click="document.querySelector('#attendance').submit()">
                    {{ __('Save') }}
                </x-primary-button>
            </div>
        </div>
    </x-modal>
</x-app-layout>
