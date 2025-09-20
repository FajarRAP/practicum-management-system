<x-modal name="add-shift" :show="$errors->default->isNotEmpty()" focusable>
    <form method="POST" action="{{ route('shift.store') }}" class="p-6 flex flex-col gap-4">
        @csrf
        @method('POST')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Add New Shift') }}
        </h2>

        <div>
            <x-input-label for="name" value="{{ __('Shift Name') }}" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full"
                placeholder="{{ __('Example: Morning, Afternoon, Shift 1') }}" :value="old('name')" />
            <x-input-error :messages="$errors->default->get('name')" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ __('Create') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
