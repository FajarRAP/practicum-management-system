<div class="flex flex-col gap-4">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900">
            {{ __('Schedule Management') }}
        </h3>
        <x-primary-button x-data @click="$dispatch('open-modal', 'add-schedule-modal')">
            {{ __('Add Schedule') }}
        </x-primary-button>
    </div>

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-6">{{ __('Meeting') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Topic') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Date & Time') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Location') }}</th>
                    <th scope="col" class="py-3 px-6 text-right">{{ __('Action') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($practicum->schedules->sortBy('meeting_number') as $schedule)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="py-4 px-6 font-medium text-gray-900">
                            {{ $schedule->meeting_number }}
                        </th>
                        <td class="py-4 px-6">{{ $schedule->topic }}</td>
                        <td class="py-4 px-6">
                            <div>{{ \Carbon\Carbon::parse($schedule->date)->isoFormat('dddd, D MMM Y') }}</div>
                            <div class="text-xs text-gray-500">
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} -
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</div>
                        </td>
                        <td class="py-4 px-6">{{ $schedule->location }}</td>
                        <td class="py-4 px-6 text-right">
                            <div class="flex justify-end items-center space-x-4">
                                <a href="{{ route('attendance.index', [$practicum, $schedule]) }}"
                                    class="font-medium text-indigo-600 hover:underline text-xs">
                                    {{ __('Manage Attendance') }}</a>
                                <button
                                    x-on:click.prevent="editSchedule = {{ $schedule }}; action = '{{ route('schedule.update', $schedule) }}'; $dispatch('open-modal', 'edit-schedule-modal');"
                                    class="font-medium text-blue-600 hover:underline text-xs">{{ __('Edit') }}</button>
                                <form action="{{ route('schedule.destroy', $schedule) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this schedule?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="font-medium text-red-600 hover:underline text-xs">{{ __('Delete') }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 px-6 text-center text-gray-500">
                            {{ __('No schedule has been created for this practicum yet.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
