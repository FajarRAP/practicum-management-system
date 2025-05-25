<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance') }}
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
                                    {{ __('Day') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Time') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Shift') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Attendance') }}
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
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($announcement->datetime)->format('l') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($announcement->datetime)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $announcement->schedule->shift }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('attendance.show', $announcement) }}"
                                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('View') }}
                                        </a>
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
