<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Registered Student at') . ' ' . $schedule->course->name . ' ' . $schedule->shift }}
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
                                    {{ __('Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Student Number') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedule->enrollments as $enrollment)
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $enrollment->user->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $enrollment->user->student->student_number }}
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
