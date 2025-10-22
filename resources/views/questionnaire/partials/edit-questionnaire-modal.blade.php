<x-modal name="edit-questionnaire" focusable>
    <form method="POST" x-bind:action="action" class="p-6 flex flex-col gap-4">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Edit Questionnaire') }}
        </h2>

        <div>
            <x-input-label for="edit_title" value="{{ __('Title') }}" />
            <x-text-input id="edit_title" name="title" type="text" class="mt-1 block w-full"
                x-model="questionnaire.title" required />
            <x-input-error :messages="$errors->default->get('title')" />
        </div>


        <div>
            <x-input-label for="edit_description" value="{{ __('Description (Optional)') }}" />
            <x-textarea id="edit_description" name="description" rows="4" class="mt-1 block w-full"
                x-model="questionnaire.description"></x-textarea>
            <x-input-error :messages="$errors->default->get('description')" />
        </div>

        <div>
            <label for="edit_is_active" class="inline-flex items-center">
                <input id="edit_is_active" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active"
                    value="1" :checked="questionnaire.is_active">
                <span class="ms-2 text-sm text-gray-600">{{ __('Set as active questionnaire') }}</span>
            </label>

            <x-input-error :messages="$errors->default->get('is_active')" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ __('Save Changes') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
