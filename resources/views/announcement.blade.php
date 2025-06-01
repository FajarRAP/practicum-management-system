@extends('layouts.announcement')

@section('add-announcement-button')
    @hasrole('assistant')
        <x-primary-button class="self-end" x-data @click="$dispatch('open-modal', 'add-announcement')">
            {{ __('Add Announcement') }}
        </x-primary-button>
    @endhasrole
@endsection

@section('approve-announcement-header')
    @hasrole('lab_tech')
        <th scope="col" class="px-6 py-3">
            {{ __('Actions') }}
        </th>
    @endhasrole
@endsection

@section('is-approved-header')
    @unlessrole('student')
        <th scope="col" class="px-6 py-3">
            {{ __('Is Approved') }}
        </th>
    @endunlessrole
@endsection

@section('add-announcement-modal')
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
                                    @if ($schedule->shift)
                                        {{ $schedule->course->name . ' - ' . $schedule->shift . ' - ' . \Carbon\Carbon::parse($schedule->time)->format('H:i') }}
                                    @else
                                        {{ $schedule->course->name . ' - ' . \Carbon\Carbon::parse($schedule->time)->format('H:i') }}
                                    @endif
                                </option>
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
@endsection
