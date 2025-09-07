<x-app-layout x-data="{ academicYear: {}, action: '' }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Academic Year') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            <x-primary-button class="self-end" x-data @click="$dispatch('open-modal', 'add-academic-year')">
                {{ __('Add Academic Year') }}
            </x-primary-button>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Year') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Semester') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($academicYears as $academicYear)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $academicYear->year }}
                                    </th>
                                    <td class="py-4 px-6">
                                        {{ $academicYear->semester }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{-- Badge untuk status --}}
                                        @if ($academicYear->status == 'ACTIVE')
                                            <span
                                                class="px-4 py-1.5 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                                {{ __('Active') }}
                                            </span>
                                        @elseif ($academicYear->status == 'FINISHED')
                                            <span
                                                class="px-4 py-1.5 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">
                                                {{ __('Finished') }}
                                            </span>
                                        @else
                                            <span
                                                class="px-4 py-1.5 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                                                {{ __('Draft') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 flex items-center space-x-3">
                                        <button class="font-medium text-blue-600 hover:underline"
                                            x-on:click.prevent="academicYear = {{ json_encode($academicYear->only(['id', 'year', 'semester', 'status'])) }};
                                            action = '{{ route('academic-year.update', $academicYear) }}';
                                            $dispatch('open-modal', 'edit-academic-year');">
                                            {{ __('Edit') }}
                                        </button>

                                        <form action="{{ route('academic-year.destroy', $academicYear) }}"
                                            method="POST"
                                            onsubmit="return confirm('{{ __('Are you sure you want to delete this data?') }}');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="font-medium text-red-600 hover:underline">{{ __('Delete') }}</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                                        {{ __('There is no academic years data.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $academicYears->links('components.pagination.pagination') }}
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-academic-year" :show="$errors->default->isNotEmpty()" focusable>
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
                        <option value="ODD" @if (old('semester') == 'ODD') selected @endif>{{ __('ODD') }}
                        </option>
                        <option value="EVEN" @if (old('semester') == 'EVEN') selected @endif>{{ __('EVEN') }}
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

    <x-modal name="edit-academic-year" :show="$errors->default->isNotEmpty()" focusable>
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
                        <option value="ODD" @if (old('semester') == 'ODD') selected @endif>{{ __('ODD') }}
                        </option>
                        <option value="EVEN" @if (old('semester') == 'EVEN') selected @endif>{{ __('EVEN') }}
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
</x-app-layout>
