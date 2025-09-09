<x-modal name="add-schedule-modal" :show="$errors->addSchedule->isNotEmpty()" focusable>
    <form method="POST" action="{{ route('schedule.store') }}" class="p-6">
        @csrf
        <input type="hidden" name="practicum_id" value="{{ $practicum->id }}">

        <h2 class="text-lg font-medium text-gray-900">{{ __('Add New Schedule') }}</h2>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="meeting_number" value="{{ __('Meeting Number') }}" />
                <x-text-input id="meeting_number" name="meeting_number" type="number" class="mt-1 block w-full"
                    :value="old('meeting_number')" required />
                <x-input-error :messages="$errors->addSchedule->get('meeting_number')" />
            </div>
            <div>
                <x-input-label for="date" value="{{ __('Date') }}" />
                <x-text-input id="date" name="date" type="date" class="mt-1 block w-full" :value="old('date')"
                    required />
                <x-input-error :messages="$errors->addSchedule->get('date')" />
            </div>
            <div class="col-span-2">
                <x-input-label for="topic" value="{{ __('Topic') }}" />
                <x-text-input id="topic" name="topic" type="text" class="mt-1 block w-full" :value="old('topic')"
                    required />
                <x-input-error :messages="$errors->addSchedule->get('topic')" />
            </div>
            <div>
                <x-input-label for="start_time" value="{{ __('Start Time') }}" />
                <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full"
                    :value="old('start_time')" required />
                <x-input-error :messages="$errors->addSchedule->get('start_time')" />
            </div>
            <div>
                <x-input-label for="end_time" value="{{ __('End Time') }}" />
                <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full" :value="old('end_time')"
                    required />
                <x-input-error :messages="$errors->addSchedule->get('end_time')" />
            </div>
            <div class="col-span-2">
                <x-input-label for="location" value="{{ __('Location') }}" />
                <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
                    placeholder="e.g. Lab 1, Online" :value="old('location')" />
                <x-input-error :messages="$errors->addSchedule->get('location')" />
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button class="ms-3">{{ __('Save Schedule') }}</x-primary-button>
        </div>
    </form>
</x-modal>
