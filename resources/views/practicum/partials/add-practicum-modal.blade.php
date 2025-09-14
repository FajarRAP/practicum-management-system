<x-modal name="add-practicum" :show="$errors->default->isNotEmpty()" focusable>
    <form method="POST" action="{{ route('practicum.store') }}" class="p-6 flex flex-col gap-4">
        @csrf
        @method('POST')

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Open New Practicum') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Select the course, academic year, and shift to open a new practicum. Existing combinations will not be available.') }}
        </p>

        <div class="mt-4">
            <x-input-label for="course_id" value="{{ __('Course') }}" />
            <x-select-input id="course_id" name="course_id" class="mt-1 block w-full">
                <x-slot name="options">
                    <option value="" disabled selected>{{ __('Select Course') }}</option>
                    @foreach ($courses as $course)
                        <option value="{{ $course->id }}" @if (old('course_id') == $course->id) selected @endif>
                            {{ $course->name }}
                        </option>
                    @endforeach
                </x-slot>
            </x-select-input>
            <x-input-error :messages="$errors->default->get('course_id')" />
        </div>

        <div>
            <x-input-label for="academic_year_id" value="{{ __('Academic Year') }}" />
            <x-select-input id="academic_year_id" name="academic_year_id" class="mt-1 block w-full">
                <x-slot name="options">
                    <option value="" disabled selected>{{ __('Select Academic Year') }}</option>
                    @foreach ($academicYears as $academicYear)
                        <option value="{{ $academicYear->id }}" @if (old('academic_year_id') == $academicYear->id) selected @endif>
                            {{ $academicYear->year }} - {{ $academicYear->semester }}
                        </option>
                    @endforeach
                </x-slot>
            </x-select-input>
            <x-input-error :messages="$errors->default->get('academic_year_id')" />
        </div>

        <div>
            <x-input-label for="shift_id" value="{{ __('Shift') }}" />
            <x-select-input id="shift_id" name="shift_id" class="mt-1 block w-full">
                <x-slot name="options">
                    <option value="" disabled selected>{{ __('Select Shift') }}</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}" @if (old('shift_id') == $shift->id) selected @endif>
                            {{ $shift->name }}
                        </option>
                    @endforeach
                </x-slot>
            </x-select-input>
            <x-input-error :messages="$errors->default->get('shift_id')" />
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-primary-button class="ms-3">
                {{ __('Open Practicum') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
