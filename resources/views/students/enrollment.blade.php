<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enrolled Practicum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            @can('practicum.enroll')
                <div class="flex justify-end">
                    <x-primary-button x-data x-on:click="$dispatch('open-modal', 'add-enrollment')">
                        {{ __('Enroll New Practicum') }}
                    </x-primary-button>
                </div>
            @endcan

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 font-semibold">
                    {{ __('My Enrollment History') }}
                </div>
                <div class="relative overflow-x-auto min-h-96">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Practicum') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Academic Year') }}
                                </th>
                                {{-- <th scope="col" class="px-6 py-3">
                                    {{ __('Status') }}
                                </th> --}}
                                {{-- <th scope="col" class="px-6 py-3">
                                    {{ __('Action') }}
                                </th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($enrollments as $enrollment)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                        {{-- [FIX] Menggunakan relasi practicum --}}
                                        <div>{{ $enrollment->practicum->course->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $enrollment->practicum->shift->name }}
                                        </div>
                                    </th>
                                    <td class="px-6 py-4">
                                        {{ $enrollment->practicum->academicYear->year }} -
                                        {{ $enrollment->practicum->academicYear->semester }}
                                    </td>
                                    {{-- <td class="px-6 py-4">
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
                                    {{-- <td class="px-6 py-4">
                                        @if ($enrollment->status == 'PENDING')
                                            <form action="{{ route('enrollment.destroy', $enrollment) }}"
                                                method="POST"
                                                onsubmit="return confirm('Are you sure you want to cancel this enrollment?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="font-medium text-red-600 hover:underline text-xs">Cancel</button>
                                            </form>
                                        @elseif ($enrollment->status == 'REJECTED')
                                            <div class="text-xs text-gray-500 italic"
                                                title="{{ $enrollment->rejection_reason }}">
                                                See reason
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td> --}}
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                        {{ __('You have not enrolled in any practicum yet.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @can('practicum.enroll')
        <x-modal name="add-enrollment" :show="$errors->addEnrollment->isNotEmpty()" focusable>
            <form method="POST" action="{{ route('enrollment.store') }}" class="p-6 flex flex-col gap-4"
                enctype="multipart/form-data">
                @csrf
                @method('POST')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Enroll to a New Practicum') }}
                </h2>

                <div>
                    <x-input-label for="practicum_id" value="{{ __('Practicum') }}" />
                    <x-select-input id="practicum_id" name="practicum_id" class="mt-1 block w-full">
                        <x-slot name="options">
                            <option value="" disabled selected>{{ __('Select Practicum') }}</option>
                            @foreach ($practicums as $practicum)
                                <option value="{{ $practicum->id }}" @if (old('practicum_id') == $practicum->id) selected @endif>
                                    {{ $practicum->course->name . ' - ' . $practicum->shift->name . ' (' . $practicum->academicYear->year . ' ' . $practicum->academicYear->semester . ')' }}
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select-input>
                    <x-input-error :messages="$errors->addEnrollment->get('practicum_id')" />
                </div>

                <div>
                    <x-input-label for="study_plan" :value="__('Study Plan (*.pdf)')" />
                    <x-file-input id="study_plan" name="study_plan" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->addEnrollment->get('study_plan')" />
                </div>

                <div>
                    <x-input-label for="transcript" :value="__('Transcript (*.pdf)')" />
                    <x-file-input id="transcript" name="transcript" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->addEnrollment->get('transcript')" />
                </div>

                <div>
                    <x-input-label for="photo" :value="__('Photo (*.jpg, *.jpeg, *.png)')" />
                    <x-file-input id="photo" name="photo" class="mt-1 block w-full" />
                    <x-input-error :messages="$errors->addEnrollment->get('photo')" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
                    <x-primary-button class="ms-3">
                        {{ __('Submit Enrollment') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    @endcan
</x-app-layout>
