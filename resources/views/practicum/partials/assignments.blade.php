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
                        <th scope="col" class="py-3 px-6">Title</th>
                        <th scope="col" class="py-3 px-6">Deadline</th>
                        <th scope="col" class="py-3 px-6">Submissions</th>
                        <th scope="col" class="py-3 px-6 text-right">Action</th>
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
                                    <a href="#" class="font-medium text-indigo-600 hover:underline text-xs">View
                                        Submissions</a>
                                    <button
                                        x-on:click.prevent="editAssignment = {{ $assignment }}; action = '{{ route('assignment.update', [$practicum, $assignment]) }}'; $dispatch('open-modal', 'edit-assignment-modal');"
                                        class="font-medium text-blue-600 hover:underline text-xs">Edit</button>
                                    <form action="{{ route('assignment.destroy', [$practicum, $assignment]) }}"
                                        method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="font-medium text-red-600 hover:underline text-xs">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-4 px-6 text-center text-gray-500">
                                No assignments have been created yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Bagian 2: Rekapitulasi Nilai Akhir --}}
    <div>
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">
                {{ __('Final Score Recapitulation') }}
            </h3>
            <x-secondary-button>
                {{ __('Export to Excel') }}
            </x-secondary-button>
        </div>
        <div class="p-6 bg-white border shadow-sm sm:rounded-lg text-center text-gray-500">
            <p>{{ __('The final score summary table will be displayed here.') }}</p>
        </div>
    </div>
</div>
