<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Announcement') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            @hasrole('assistant')
                <x-primary-button class="self-end" x-data @click="$dispatch('open-modal', 'add-announcement')">
                    {{ __('Add Announcement') }}
                </x-primary-button>
            @endhasrole

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
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Activity') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Day') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Date') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Time') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Place') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($announcements as $announcement)
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $announcement->schedule->course->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $announcement->schedule->shift->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $announcement->activity }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Carbon\Carbon::parse($announcement->datetime)->format('l') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Carbon\Carbon::parse($announcement->datetime)->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Carbon\Carbon::parse($announcement->datetime)->format('H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $announcement->place }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $announcements->links('components.pagination.pagination') }}
                </div>
            </div>
        </div>
    </div>

    @hasrole('assistant')
        <x-modal name="add-announcement" :show="$errors->addAnnouncement->isNotEmpty()" focusable>
            <form method="POST" action="{{ route('announcement.store') }}" class="p-6 flex flex-col gap-4">
                @csrf
                @method('POST')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Add Announcement') }}
                </h2>

                <div>
                    <x-input-label for="schedule" value="{{ __('Schedule') }}" />
                    <x-select-input id="schedule" name="schedule" class="mt-1 block w-3/4">
                        <x-slot name="options">
                            <option value="" disabled selected>{{ __('Select') . ' ' . __('Schedule') }}</option>
                            @foreach ($schedules as $schedule)
                                <option value="{{ $schedule->id }}">
                                    {{ $schedule->course->name . ' - ' . $schedule->shift->name }}</option>
                            @endforeach
                        </x-slot>
                    </x-select-input>
                    <x-input-error :messages="$errors->addAnnouncement->get('schedule')" />
                </div>
                <div>
                    <x-input-label for="datetime" value="{{ __('Date Time') }}" />
                    <x-text-input id="datetime" name="datetime" type="datetime-local" class="mt-1 block w-3/4"
                        placeholder="{{ __('Date Time') }}" />
                    <x-input-error :messages="$errors->addAnnouncement->get('datetime')" />
                </div>
                <div>
                    <x-input-label for="activity" value="{{ __('Activity') }}" />
                    <x-text-input id="activity" name="activity" type="text" class="mt-1 block w-3/4"
                        placeholder="{{ __('Activity') }}" />
                    <x-input-error :messages="$errors->addAnnouncement->get('activity')" />
                </div>
                <div>
                    <x-input-label for="place" value="{{ __('Place') }}" />
                    <x-text-input id="place" name="place" type="text" class="mt-1 block w-3/4"
                        placeholder="{{ __('Place') }}" />
                    <x-input-error :messages="$errors->addAnnouncement->get('place')" />
                </div>
                <div>
                    <label for="is_schedule_announcement" class="inline-flex items-center">
                        <input id="is_schedule_announcement" type="checkbox"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                            name="is_schedule_announcement" checked>
                        <span class="ms-2 text-sm text-gray-600">{{ __('Schedule Announcement') }}</span>
                    </label>
                </div>

                <div class="flex justify-end">
                    <x-secondary-button @click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3">
                        {{ __('Add Announcement') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    @endhasrole
</x-app-layout>
