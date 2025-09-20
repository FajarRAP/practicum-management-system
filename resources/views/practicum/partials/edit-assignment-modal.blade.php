<x-modal name="edit-assignment-modal" :show="$errors->updateAssignment->isNotEmpty()" focusable>
    <form method="POST" x-bind:action="action" class="p-6" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <h2 class="text-lg font-medium text-gray-900">{{ __('Edit Assignment') }}</h2>
        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="edit_title" value="{{ __('Title') }}" />
                <x-text-input id="edit_title" name="title" type="text" class="mt-1 block w-full"
                    x-model="editAssignment.title" required />
            </div>
            <div>
                <x-input-label for="edit_description" value="{{ __('Description') }}" />
                <x-textarea id="edit_description" name="description" class="mt-1 block w-full"
                    x-model="editAssignment.description"></x-textarea>
            </div>
            <div>
                <x-input-label for="edit_deadline" value="{{ __('Deadline') }}" />
                <x-text-input id="edit_deadline" name="deadline" type="datetime-local" class="mt-1 block w-full"
                    x-model="editAssignment.deadline" required />
            </div>
            <div>
                <x-input-label for="edit_file" value="{{ __('New Attachment (Optional)') }}" />
                <x-file-input id="edit_file" name="file_path" class="mt-1 block w-full" />
                <p class="mt-1 text-xs text-gray-500">
                    {{ __('Uploading a new file will replace the old one.') }}</p>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button class="ms-3">{{ __('Save Changes') }}</x-primary-button>
        </div>
    </form>
</x-modal>
