<x-modal name="add-academic-year" focusable>
    <form method="POST" action="{{ route('academic-year.store') }}" class="p-6 flex flex-col gap-4">
        @csrf
        @method('POST')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Add Academic Year') }}
        </h2>

        <div>
            <x-input-label for="year" value="{{ __('Academic Year (Example: 2025/2026)') }}" />
            <x-text-input id="year" name="year" type="text" class="mt-1 block w-full"
                placeholder="{{ __('2025/2026') }}" :value="old('year')" />
            <x-input-error :messages="$errors->default->get('year')" />
        </div>

        <div>
            <x-input-label for="semester" value="{{ __('Semester') }}" />
            <x-select-input id="semester" name="semester" class="mt-1 block w-full">
                <x-slot name="options">
                    <option value="" disabled selected>{{ __('Select Semester') }}</option>
                    <option value="GANJIL" @if (old('semester') == 'GANJIL') selected @endif>{{ __('GANJIL') }}
                    </option>
                    <option value="GENAP" @if (old('semester') == 'GENAP') selected @endif>{{ __('GENAP') }}
                    </option>
                </x-slot>
            </x-select-input>
            <x-input-error :messages="$errors->default->get('semester')" />
        </div>

        <div>
            <x-input-label for="status" value="{{ __('Status (Default DRAFT)') }}" />
            <x-select-input id="status" name="status" class="mt-1 block w-full">
                <x-slot name="options">
                    <option value="" disabled selected>{{ __('Select Status') }}</option>
                    <option value="DRAFT" @if (old('status') == 'DRAFT') selected @endif>{{ __('DRAFT') }}
                    </option>
                    <option value="ACTIVE" @if (old('status') == 'ACTIVE') selected @endif>{{ __('ACTIVE') }}
                    </option>
                    <option value="FINISHED" @if (old('status') == 'FINISHED') selected @endif>{{ __('FINISHED') }}
                    </option>
                </x-slot>
            </x-select-input>
            <x-input-error :messages="$errors->default->get('status')" />
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
