<x-modal name="edit-question-modal" focusable>
    <form method="POST" x-bind:action="action" class="p-6" x-data="{ content: '', type: 'radio', options: [''] }"
        x-on:open-modal.window="if ($event.detail === 'edit-question-modal') {
              content = questionToEdit.content;
              type = questionToEdit.type;
              const parsedOptions = JSON.parse(questionToEdit.options || '[]');
              options = parsedOptions.length > 0 ? parsedOptions : [''];
          }">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-medium text-gray-900">{{ __('Edit Question') }}</h2>
        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="edit_content" value="{{ __('Question Content') }}" />
                {{-- Menggunakan x-model untuk binding data --}}
                <x-textarea id="edit_content" name="content" rows="3" class="mt-1 block w-full" x-model="content"
                    required></x-textarea>
            </div>
            <div>
                <x-input-label for="edit_type" value="{{ __('Question Type') }}" />
                <x-select-input id="edit_type" name="type" x-model="type" class="mt-1 block w-full">
                    <x-slot name="options">
                        <option value="radio">Radio Button (Single Choice)</option>
                        <option value="checkbox">Checkbox (Multiple Choice)</option>
                        <option value="text">Text (Open Answer)</option>
                    </x-slot>
                </x-select-input>
            </div>
            <div x-show="type === 'radio' || type === 'checkbox'" class="border-t pt-4">
                <label class="font-medium text-gray-700 text-sm">Options</label>
                <div class="mt-2 space-y-2">
                    <template x-for="(option, index) in options" :key="index">
                        <div class="flex items-center gap-2">
                            <x-text-input type="text" name="options[]" x-model="options[index]" class="w-full"
                                placeholder="Option text..." />
                            <button type="button" x-on:click="options.splice(index, 1)" x-show="options.length > 1"
                                class="text-red-500">&times;</button>
                        </div>
                    </template>
                </div>
                <x-secondary-button type="button" x-on:click="options.push('')" class="mt-2 text-xs">Add
                    Option</x-secondary-button>
            </div>
        </div>
        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-primary-button class="ms-3">{{ __('Save Changes') }}</x-primary-button>
        </div>
    </form>
</x-modal>
