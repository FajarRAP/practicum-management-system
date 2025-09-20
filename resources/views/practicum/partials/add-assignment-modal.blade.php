<x-modal name="add-assignment-modal" :show="$errors->addAssignment->isNotEmpty()" focusable>
    <form method="POST" action="{{ route('assignment.store') }}" class="p-6" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="practicum_id" value="{{ $practicum->id }}">

        <h2 class="text-lg font-medium text-gray-900">{{ __('Create New Assignment') }}</h2>
        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="schedule_id" value="{{ __('Schedule') }}" />
                <x-select-input id="schedule_id" name="schedule_id" class="mt-1 block w-full">
                    <x-slot name="options">
                        <option value="" disabled selected>{{ __('Select Schedule') }}</option>
                        @foreach ($practicum->schedules as $schedule)
                            <option value="{{ $schedule->id }}" @if (old('schedule_id') == $schedule->id) selected @endif>
                                {{ __('Schedule ') }} {{ $schedule->meeting_number }} - {{ $schedule->topic }}
                            </option>
                        @endforeach
                    </x-slot>
                </x-select-input>
                <x-input-error :messages="$errors->addAssignment->get('schedule_id')" />
            </div>
            <div>
                <x-input-label for="title" value="{{ __('Title') }}" />
                <x-text-input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')"
                    required />
                <x-input-error :messages="$errors->addAssignment->get('title')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="description" value="{{ __('Description') }}" />
                <x-textarea id="description" name="description"
                    class="mt-1 block w-full">{{ old('description') }}</x-textarea>
                <x-input-error :messages="$errors->addAssignment->get('description')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="deadline" value="{{ __('Deadline') }}" />
                <x-text-input id="deadline" name="deadline" type="datetime-local" class="mt-1 block w-full"
                    :value="old('deadline')" required />
                <x-input-error :messages="$errors->addAssignment->get('deadline')" class="mt-2" />
            </div>
            <div>
                <x-input-label for="file" value="{{ __('Attachment (Optional)') }}" />
                <x-file-input id="file" name="file_path" class="mt-1 block w-full" />
                <x-input-error :messages="$errors->addAssignment->get('file')" class="mt-2" />
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button class="ms-3">{{ __('Create Assignment') }}</x-primary-button>
        </div>
    </form>
</x-modal>
