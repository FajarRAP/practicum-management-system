<x-modal name="edit-shift" focusable>
    <form method="POST" x-bind:action="action" class="p-6 flex flex-col gap-4">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Edit Shift') }}
        </h2>

        <div>
            <x-input-label for="edit_name" value="{{ __('Nama Shift') }}" />
            <x-text-input id="edit_name" name="name" type="text" class="mt-1 block w-full" x-model="shift.name" />
            <x-input-error :messages="$errors->default->get('name')" />
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
