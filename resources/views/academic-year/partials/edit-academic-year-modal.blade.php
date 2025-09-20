<x-modal name="edit-academic-year" focusable>
    <form method="POST" x-bind:action="action" class="p-6 flex flex-col gap-4">
        @csrf
        @method('PUT')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Edit Academic Year') }}
        </h2>

        <div>
            <x-input-label for="edit_year" value="{{ __('Academic Year (Example: 2025/2026)') }}" />
            <x-text-input id="edit_year" name="year" type="text" class="mt-1 block w-full" :value="old('year')"
                x-model="academicYear.year" />
            <x-input-error :messages="$errors->default->get('year')" />
        </div>

        <div>
            <x-input-label for="edit_semester" value="{{ __('Semester') }}" />
            <x-select-input id="edit_semester" name="semester" x-model="academicYear.semester"
                class="mt-1 block w-full">
                <x-slot name="options">
                    <option value="GANJIL" @if (old('semester') == 'GANJIL') selected @endif>{{ __('GANJIL') }}
                    </option>
                    <option value="GENAP" @if (old('semester') == 'GENAP') selected @endif>{{ __('GENAP') }}
                    </option>
                </x-slot>
            </x-select-input>
            <x-input-error :messages="$errors->default->get('semester')" />
        </div>

        <div>
            <x-input-label for="edit_status" value="{{ __('Status') }}" />
            <x-select-input id="edit_status" name="status" x-model="academicYear.status" class="mt-1 block w-full">
                <x-slot name="options">
                    <option value="DRAFT" @if (old('status') == 'DRAFT') selected @endif>{{ __('DRAFT') }}
                    </option>
                    <option value="ACTIVE" @if (old('status') == 'ACTIVE') selected @endif>{{ __('ACTIVE') }}
                    </option>
                    <option value="FINISHED" @if (old('status') == 'FINISHED') selected @endif>
                        {{ __('FINISHED') }}
                    </option>
                </x-slot>
            </x-select-input>
            <x-input-error :messages="$errors->default->get('status')" />
        </div>

        <div class="flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ __('Save Changes') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
