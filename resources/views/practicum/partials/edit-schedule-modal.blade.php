<x-modal name="edit-schedule-modal" :show="$errors->updateSchedule->isNotEmpty()" focusable>
    <form method="POST" x-bind:action="action" class="p-6">
        @csrf
        @method('PUT')
        <h2 class="text-lg font-medium text-gray-900">{{ __('Edit Schedule') }}</h2>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <x-input-label for="edit_meeting_number" value="{{ __('Meeting Number') }}" />
                <x-text-input id="edit_meeting_number" name="meeting_number" type="number" class="mt-1 block w-full"
                    x-model="editSchedule.meeting_number" required />
            </div>
            <div>
                <x-input-label for="edit_date" value="{{ __('Date') }}" />
                <x-text-input id="edit_date" name="date" type="date" class="mt-1 block w-full"
                    x-model="editSchedule.date" required />
            </div>
            <div class="col-span-2">
                <x-input-label for="edit_topic" value="{{ __('Topic') }}" />
                <x-text-input id="edit_topic" name="topic" type="text" class="mt-1 block w-full"
                    x-model="editSchedule.topic" required />
            </div>
            <div>
                <x-input-label for="edit_start_time" value="{{ __('Start Time') }}" />
                <x-text-input id="edit_start_time" name="start_time" type="time" class="mt-1 block w-full"
                    x-model="editSchedule.start_time" required />
            </div>
            <div>
                <x-input-label for="edit_end_time" value="{{ __('End Time') }}" />
                <x-text-input id="edit_end_time" name="end_time" type="time" class="mt-1 block w-full"
                    x-model="editSchedule.end_time" required />
            </div>
            <div class="col-span-2">
                <x-input-label for="edit_location" value="{{ __('Location') }}" />
                <x-text-input id="edit_location" name="location" type="text" class="mt-1 block w-full"
                    x-model="editSchedule.location" />
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="console.log(action)">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button class="ms-3">{{ __('Save Changes') }}</x-primary-button>
        </div>
    </form>
</x-modal>
