<div class="flex flex-col gap-4">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-medium text-gray-900">
            {{ __('Enrollment Management') }}
        </h3>
        <span class="text-sm text-gray-500">
            {{ $practicum->enrollments->count() }} {{ __('students enrolled') }}
        </span>
    </div>

    <div class="overflow-x-auto relative shadow-md sm:rounded-lg max-h-80">
        <table class="w-full text-sm text-left text-gray-500">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th scope="col" class="py-3 px-6">{{ __('Student') }}</th>
                    <th scope="col" class="py-3 px-6">{{ __('Enrollment Date') }}</th>
                    @can('student_datas.view')
                        <th scope="col" class="py-3 px-6">{{ __('Files') }}</th>
                    @endcan
                    {{-- <th scope="col" class="py-3 px-6">Status</th> --}}
                    {{-- <th scope="col" class="py-3 px-6 text-right">Action</th> --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($practicum->enrollments as $enrollment)
                    <tr class="bg-white border-b hover:bg-gray-50">
                        <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                            <div>{{ $enrollment->user->name }}</div>
                            <div class="text-xs text-gray-500">
                                {{ $enrollment->user->identity_number }}
                            </div>
                        </th>
                        <td class="py-4 px-6">
                            {{ $enrollment->created_at->isoFormat('D MMMM YYYY') }}
                        </td>
                        @can('student_datas.view')
                            <td class="py-4 px-6 flex gap-3">
                                <a href="{{ Storage::url($enrollment->study_plan_path) }}" target="_blank"
                                    class="font-medium text-blue-600 hover:underline">{{ __('Study Plan') }}</a>
                                <a href="{{ Storage::url($enrollment->transcript_path) }}" target="_blank"
                                    class="font-medium text-blue-600 hover:underline">{{ __('Transcript') }}</a>
                                <a href="{{ Storage::url($enrollment->photo_path) }}" target="_blank"
                                    class="font-medium text-blue-600 hover:underline">{{ __('Photo') }}</a>
                            </td>
                        @endcan
                        {{-- <td class="py-4 px-6">
                            @if ($enrollment->status == 'APPROVED')
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">Approved</span>
                            @elseif ($enrollment->status == 'PENDING')
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full text-xs">Pending</span>
                            @else
                                <span
                                    class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full text-xs">Rejected</span>
                            @endif
                        </td> --}}
                        {{-- <td class="py-4 px-6 text-right">
                            @if ($enrollment->status == 'PENDING')
                                <div class="flex justify-end space-x-3">
                                    <form action="{{ route('enrollment.approve', $enrollment) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                            class="font-medium text-green-600 hover:underline text-xs">Approve</button>
                                    </form>
                                    <button
                                        x-on:click.prevent="
                                        action = '{{ route('enrollment.reject', $enrollment) }}';
                                        $dispatch('open-modal', 'reject-enrollment');"
                                        class="font-medium text-red-600 hover:underline text-xs">
                                        Reject
                                    </button>
                                </div>
                            @else
                                <span class="text-xs text-gray-400 italic">Processed</span>
                            @endif
                        </td> --}}
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-4 px-6 text-center text-gray-500">
                            {{ __('No students have enrolled in this practicum yet.') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
