<x-modal name="add-questionnaire" focusable>
    <form method="POST" action="{{ route('questionnaire.store') }}" class="p-6 flex flex-col gap-4">
        @csrf
        @method('POST')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Create New Questionnaire') }}
        </h2>

        <div>
            <x-input-label for="title" value="{{ __('Title') }}" />
            <x-text-input id="title" name="title" type="text" class="mt-1 block w-full"
                placeholder="{{ __('e.g., Practicum Schedule Survey 2025/2026') }}" :value="old('title')" required />
            <x-input-error :messages="$errors->default->get('title')" />
        </div>

        <div>
            <x-input-label for="description" value="{{ __('Description (Optional)') }}" />
            <x-textarea id="description" name="description" rows="4" class="mt-1 block w-full"
                placeholder="{{ __('Provide a brief explanation for this questionnaire.') }}">{{ old('description') }}</x-textarea>
            <x-input-error :messages="$errors->default->get('description')" />
        </div>

        <div>
            <label for="is_active" class="inline-flex items-center">
                <input id="is_active" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="is_active"
                    value="1" @if (old('is_active')) checked @endif>
                <span class="ms-2 text-sm text-gray-600">{{ __('Set as active questionnaire') }}</span>
            </label>
            <x-input-error :messages="$errors->default->get('is_active')" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ __('Create Questionnaire') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
