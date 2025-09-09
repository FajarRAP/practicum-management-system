<div class="flex flex-col gap-8">
    {{-- Bagian 1: Manajemen Tugas --}}
    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                {{ __('Assignment Management') }}
            </h3>
            <x-primary-button x-data @click="$dispatch('open-modal', 'add-assignment-modal')">
                {{ __('Create New Assignment') }}
            </x-primary-button>
        </div>

        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3 px-6">{{ __('Title') }}</th>
                        <th scope="col" class="py-3 px-6">{{ __('Deadline') }}</th>
                        <th scope="col" class="py-3 px-6">{{ __('Submissions') }}</th>
                        <th scope="col" class="py-3 px-6 text-right">{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($practicum->assignments as $assignment)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900">
                                {{ $assignment->title }}
                            </th>
                            <td class="py-4 px-6">
                                {{ \Carbon\Carbon::parse($assignment->deadline)->isoFormat('dddd, D MMM Y, HH:mm') }}
                            </td>
                            <td class="py-4 px-6">
                                {{ $assignment->submissions->count() }} /
                                {{-- If has status column enrollemnts table --}}
                                {{-- {{ $practicum->enrollments->where('status', 'APPROVED')->count() }} --}}
                                {{ $practicum->enrollments->count() }}
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="flex justify-end items-center space-x-4">
                                    <a href="{{ route('assignment-submission.index', [$practicum, $assignment]) }}"
                                        class="font-medium text-indigo-600 hover:underline text-xs">{{ __('View Submissions') }}</a>
                                    <button
                                        x-on:click.prevent="editAssignment = {{ $assignment }}; action = '{{ route('assignment.update', [$practicum, $assignment]) }}'; $dispatch('open-modal', 'edit-assignment-modal');"
                                        class="font-medium text-blue-600 hover:underline text-xs">{{ __('Edit') }}</button>
                                    <form action="{{ route('assignment.destroy', [$practicum, $assignment]) }}"
                                        method="POST" onsubmit="return confirm('Are you sure?');">
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
                            <td colspan="4" class="py-4 px-6 text-center text-gray-500">
                                {{ __('No assignments have been created yet.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                {{ __('Final Score Recapitulation') }}
            </h3>
            <div class="flex space-x-2">

                <form method="POST" action="{{ route('practicum.calculate-scores', $practicum) }}">
                    @csrf
                    <x-secondary-button type="submit">
                        {{ __('Calculate Scores') }}
                    </x-secondary-button>
                </form>
                {{-- <x-secondary-button>
                    {{ __('Export to Excel') }}
                </x-secondary-button> --}}
            </div>
        </div>

        <div class="overflow-x-auto relative bg-white shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th class="py-3 px-6">#</th>
                        <th class="py-3 px-6">{{ __('Student Name') }}</th>
                        <th class="py-3 px-6">{{ __('Student ID') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Active Score') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Report Score') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Final Score') }}</th>
                        <th class="py-3 px-6 text-center">{{ __('Final Grade') }}</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- ->where('status', 'APPROVED') --}}
                    @forelse ($practicum->enrollments as $enrollment)
                        <tr class="bg-white border-b hover:bg-gray-50">
                            <td class="py-4 px-6">{{ $loop->iteration }}</td>
                            <th scope="row" class="py-4 px-6 font-medium text-gray-900">
                                {{ $enrollment->user->name }}
                            </th>
                            <td class="py-4 px-6">{{ $enrollment->user->identity_number }}</td>
                            <td class="py-4 px-6 text-center">{{ number_format($enrollment->active_score, 2) }}</td>
                            <td class="py-4 px-6 text-center">{{ number_format($enrollment->report_score, 2) }}</td>
                            <td class="py-4 px-6 text-center font-bold">
                                {{ number_format($enrollment->final_score, 2) }}</td>
                            <td class="py-4 px-6 text-center">
                                @if ($enrollment->final_grade == 'A')
                                    <span
                                        class="px-3 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">A</span>
                                @elseif (in_array($enrollment->final_grade, ['B', 'C']))
                                    <span
                                        class="px-3 py-1 font-semibold leading-tight text-blue-700 bg-blue-100 rounded-full">{{ $enrollment->final_grade }}</span>
                                @else
                                    <span
                                        class="px-3 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full">{{ $enrollment->final_grade ?? 'E' }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 px-6 text-center text-gray-500">
                                {{ __('No approved students to display scores for.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
