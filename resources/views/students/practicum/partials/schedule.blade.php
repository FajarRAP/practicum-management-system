<div class="flex flex-col gap-4">
    <h3 class="text-lg font-medium text-gray-900">
        {{ __('Practicum Schedule & Journal') }}</h3>
    <div class="overflow-x-auto relative shadow-md sm:rounded-lg max-h-80">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="py-3 px-6">{{ __('Meeting') }}</th>
                    <th class="py-3 px-6">{{ __('Topic') }}</th>
                    <th class="py-3 px-6">{{ __('Date & Time') }}</th>
                    <th class="py-3 px-6 text-center">{{ __('My Journal') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($practicum->schedules->where('status', 'APPROVED')->sortBy('meeting_number') as $schedule)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <td class="py-4 px-6 font-medium">
                            {{ $schedule->meeting_number }}
                        </td>
                        <td class="py-4 px-6">{{ $schedule->topic }}</td>
                        <td class="py-4 px-6">
                            {{ \Carbon\Carbon::parse($schedule->date)->isoFormat('D MMM Y') }},
                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                        </td>
                        <td class="py-4 px-6 text-center">

                            @php
                                $attendance = $myAttendances->get($schedule->id);
                            @endphp

                            @if ($attendance)
                                <button class="font-medium text-indigo-600 hover:underline text-sm"
                                    x-on:click.prevent="myJournal = {{ json_encode($attendance) }};
                                                                $dispatch('open-modal', 'view-journal-modal');">
                                    {{ __('View Details') }}
                                </button>
                            @else
                                <span class="text-xs text-gray-400 italic">{{ __('No record') }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 px-6 text-center">
                            {{ __('No schedule available yet.') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
