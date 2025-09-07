<x-app-layout>
    {{-- NANTI ADA ABSENSI ASISTEN JUGA --}}
    <x-slot name="header">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Attendance Management') }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                {{ $schedule->practicum->course->name }} - {{ __('Meeting') }} {{ $schedule->meeting_number }}
            </p>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('attendance.store', [$practicum, $schedule]) }}">
                @csrf
                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b">
                        <h3 class="font-semibold">{{ $schedule->topic }}</h3>
                        <p class="text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($schedule->date)->isoFormat('dddd, D MMMM Y') }}</p>
                    </div>

                    <div class="overflow-x-auto min-h-96">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="py-3 px-6">#</th>
                                    <th class="py-3 px-6">{{ __('Student Name') }}</th>
                                    <th class="py-3 px-6">{{ __('Student Identity Number') }}</th>
                                    <th class="py-3 px-6">{{ __('Attendance Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($schedule->practicum->enrollments as $enrollment)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="py-4 px-6">{{ $loop->iteration }}</td>
                                        <th scope="row" class="py-4 px-6 font-medium text-gray-900">
                                            {{ $enrollment->user->name }}
                                        </th>
                                        <td class="py-4 px-6">{{ $enrollment->user->identity_number }}</td>
                                        <td class="py-4 px-6">
                                            <div class="flex items-center space-x-4">
                                                @php
                                                    $currentStatus = $attendances[$enrollment->user_id]->status ?? null;
                                                @endphp
                                                @foreach (['PRESENT', 'EXCUSED', 'SICK', 'ABSENT'] as $status)
                                                    <label class="flex items-center">
                                                        <input type="radio"
                                                            name="attendances[{{ $enrollment->user_id }}]"
                                                            value="{{ $status }}" class="text-indigo-600"
                                                            {{ $currentStatus == $status ? 'checked' : '' }}>
                                                        <span class="ms-2">{{ $status }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 px-6 text-center">
                                            {{ __('There are no approved students in this practicum yet.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-4 flex justify-end">
                    <x-primary-button>
                        {{ __('Save Attendance') }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
