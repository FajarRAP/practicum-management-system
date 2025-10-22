<x-modal name="add-question-modal" focusable>
    <form method="POST" action="{{ route('question.store', $questionnaire) }}" class="p-6" x-data="{ type: 'radio', options: [''] }">
        @csrf
        <h2 class="text-lg font-medium text-gray-900">{{ __('Add New Question') }}</h2>
        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="content" value="{{ __('Question Content') }}" />
                <x-textarea id="content" name="content" rows="3" class="mt-1 block w-full"
                    required>{{ old('content') }}</x-textarea>
            </div>
            <div>
                <x-input-label for="type" value="{{ __('Question Type') }}" />
                <x-select-input id="type" name="type" x-model="type" class="mt-1 block w-full">
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
            <x-primary-button class="ms-3">{{ __('Save Question') }}</x-primary-button>
        </div>
    </form>
</x-modal>
