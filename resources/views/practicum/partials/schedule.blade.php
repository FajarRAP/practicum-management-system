<div class="flex flex-col gap-4">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900">
            {{ __('Schedule Management') }}
        </h3>
        @hasrole('assistant')
            <x-primary-button x-data @click="$dispatch('open-modal', 'add-schedule-modal')">
                {{ __('Add Schedule') }}
            </x-primary-button>
        @endhasrole
    </div>

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-6">{{ __('Meeting') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Topic') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Date & Time') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Location') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Status') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Action') }}</th>
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
                        <td class="py-4 px-6">
                            @if ($schedule->status == 'APPROVED')
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">{{ __('Approved') }}</span>
                            @elseif ($schedule->status == 'PENDING')
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full text-xs">{{ __('Pending') }}</span>
                            @else
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full text-xs"
                                    title="{{ $schedule->rejection_reason }}">{{ __('Rejected') }}</span>
                            @endif
                        </td>
                        <td class="py-4 px-6 flex flex-col items-center text-center gap-1">
                            @hasrole('assistant')
                                @if ($schedule->status == 'APPROVED')
                                    <a href="{{ route('attendance.index', [$practicum, $schedule]) }}"
                                        class="font-medium text-indigo-600 hover:underline text-xs">
                                        {{ __('Manage Journal') }}</a>
                                @endif
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
                            @endhasrole
                            @hasrole('lab_tech')
                                <form action="{{ route('schedule.approve', $schedule) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit"
                                        class="font-medium text-green-600 hover:underline text-xs">{{ __('Approve') }}</button>
                                </form>
                                <button
                                    x-on:click.prevent="action = '{{ route('schedule.reject', $schedule) }}';
                                        $dispatch('open-modal', 'reject-schedule-modal');"
                                    class="font-medium text-red-600 hover:underline text-xs">
                                    {{ __('Reject') }}
                                </button>
                            @endhasrole
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                            {{ __('No schedule has been created for this practicum yet.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
