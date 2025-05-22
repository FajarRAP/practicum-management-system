<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assessment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
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
                                                <table
                                                    class="w-full text-sm text-left rtl:text-right text-gray-500 border-gray-300">
                                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                                        <tr>
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
                                                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                                            <td class="px-6 py-4">
                                                                {{ $announcement->status }}
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                {{ $announcement->participation_score }}
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                {{ $announcement->active_score }}
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                {{ $announcement->report_score }}
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <a href="{{ asset("storage/$announcement->file_path") }} "
                                                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                                    {{ __('View') }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <div class="flex justify-end">
                                                    <x-secondary-button @click="$dispatch('close')">
                                                        {{ __('Cancel') }}
                                                    </x-secondary-button>

                                                    <x-primary-button class="ms-3">
                                                        {{ __('Save') }}
                                                    </x-primary-button>
                                                </div>
                                            </div>
                                        </x-modal>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $announcements->links('components.pagination.pagination') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
