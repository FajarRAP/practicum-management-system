<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enrolled Practicum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            <x-primary-button class="self-end" x-data @click="$dispatch('open-modal', 'add-enrollment')">
                {{ __('Enroll') }}
            </x-primary-button>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 border-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Course') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Shift') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($enrollments as $enrollment)
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $enrollment->schedule->course->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $enrollment->schedule->shift }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-enrollment" :show="$errors->addEnrollment->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('enrollment.store') }}" class="p-6 flex flex-col gap-4">
            @csrf
            @method('POST')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Enroll') }}
            </h2>

            <div>
                <x-input-label for="schedule" value="{{ __('Schedule') }}" />
                <x-select-input id="schedule" name="schedule" class="mt-1 block w-3/4">
                    <x-slot name="options">
                        <option value="" disabled selected>{{ __('Select') . ' ' . __('Schedule') }}</option>
                        @foreach ($schedules as $schedule)
                            <option value="{{ $schedule->id }}">
                                {{ $schedule->course->name . ' - ' . $schedule->shift }}</option>
                        @endforeach
                    </x-slot>
                </x-select-input>
                <x-input-error :messages="$errors->addEnrollment->get('schedule')" />
            </div>

            <div class="flex justify-end">
                <x-secondary-button @click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Enroll') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
