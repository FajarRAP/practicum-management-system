<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Schedule') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            <x-primary-button class="self-end" x-data @click="$dispatch('open-modal', 'add-schedule')">
                {{ __('Add Schedule') }}
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
                                    {{ __('Day') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Time') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Shift') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Registered Student') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules as $schedule)
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $schedule->course->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $schedule->day->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($schedule->time)->format('H:m') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $schedule->shift->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href=""
                                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $schedules->links('components.pagination.pagination') }}
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-schedule" :show="$errors->addSchedule->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('schedule.store') }}" class="p-6 flex flex-col gap-4">
            @csrf
            @method('POST')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Add Schedule') }}
            </h2>

            <div>
                <x-input-label for="course" value="{{ __('Course') }}" />
                <x-select-input id="course" name="course" class="mt-1 block w-3/4">
                    <x-slot name="options">
                        <option value="" disabled selected>{{ __('Select') . ' ' . __('Course') }}</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->name }}</option>
                        @endforeach
                    </x-slot>
                </x-select-input>
                <x-input-error :messages="$errors->addSchedule->get('course')" />
            </div>
            <div>
                <x-input-label for="day" value="{{ __('Day') }}" />
                <x-select-input id="day" name="day" class="mt-1 block w-3/4">
                    <x-slot name="options">
                        <option value="" disabled selected>{{ __('Select') . ' ' . __('Day') }}</option>
                        @foreach ($days as $day)
                            <option value="{{ $day->id }}">{{ $day->name }}</option>
                        @endforeach
                    </x-slot>
                </x-select-input>
                <x-input-error :messages="$errors->addSchedule->get('day')" />
            </div>
            <div>
                <x-input-label for="shift" value="{{ __('Shift') }}" />
                <x-select-input id="shift" name="shift" class="mt-1 block w-3/4">
                    <x-slot name="options">
                        <option value="" disabled selected>{{ __('Select') . ' ' . __('Shift') }}</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->name }}</option>
                        @endforeach
                    </x-slot>
                </x-select-input>
                <x-input-error :messages="$errors->addSchedule->get('shift')" />
            </div>
            <div>
                <x-input-label for="time" value="{{ __('Time') }}" />
                <x-text-input id="time" name="time" type="time" class="mt-1 block w-3/4"
                    placeholder="{{ __('Time') }}" />
                <x-input-error :messages="$errors->addSchedule->get('time')" />
            </div>

            <div class="flex justify-end">
                <x-secondary-button @click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Add Schedule') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
