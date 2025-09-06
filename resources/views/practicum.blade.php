<x-app-layout x-data="{ practicum: {}, action: '' }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Practicum') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            <x-primary-button class="self-end" x-data @click="$dispatch('open-modal', 'add-practicum')">
                {{ __('Add Practicum') }}
            </x-primary-button>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Course') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Academic Year') }}
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
                            @forelse ($practicums as $practicum)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="py-4 px-6">
                                        <div class="font-medium text-gray-900">{{ $practicum->course->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $practicum->course->code }}</div>
                                    </th>

                                    <td class="py-4 px-6">
                                        {{ $practicum->academicYear->year }}
                                    </td>

                                    <td class="py-4 px-6">
                                        {{ $practicum->academicYear->semester }}
                                    </td>

                                    <td class="py-4 px-6">
                                        @if ($practicum->academicYear->status == 'ACTIVE')
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                                Aktif
                                            </span>
                                        @elseif ($practicum->academicYear->status == 'FINISHED')
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">
                                                Selesai
                                            </span>
                                        @else
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                                                Draft
                                            </span>
                                        @endif
                                    </td>

                                    <td class="py-4 px-6 flex items-center justify-start space-x-4">

                                        <a href="{{ route('practicum.show', $practicum) }}"
                                            class="font-medium text-indigo-600 hover:underline">
                                            Kelola
                                        </a>

                                        <form action="{{ route('practicum.destroy', $practicum) }}" method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this practicum?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="font-medium text-red-600 hover:underline">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 px-6 text-center text-gray-500">
                                        {{ __('No practicum data available.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $practicums->links('components.pagination.pagination') }}
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-practicum" :show="$errors->default->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('practicum.store') }}" class="p-6 flex flex-col gap-4">
            @csrf
            @method('POST')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Buka Praktikum Baru') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                {{ __('Pilih mata kuliah dan tahun ajaran untuk membuka pendaftaran praktikum baru. Kombinasi yang sudah ada tidak akan muncul di pilihan.') }}
            </p>

            {{-- Dropdown untuk memilih Mata Kuliah --}}
            <div class="mt-4">
                <x-input-label for="course_id" value="{{ __('Mata Kuliah') }}" />
                <x-select-input id="course_id" name="course_id" class="mt-1 block w-full">
                    <x-slot name="options">
                        <option value="" disabled selected>{{ __('Pilih Mata Kuliah') }}</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" @if (old('course_id') == $course->id) selected @endif>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </x-slot>
                </x-select-input>
                <x-input-error :messages="$errors->default->get('course_id')" class="mt-2" />
            </div>

            {{-- Dropdown untuk memilih Tahun Ajaran --}}
            <div>
                <x-input-label for="academic_year_id" value="{{ __('Tahun Ajaran') }}" />
                <x-select-input id="academic_year_id" name="academic_year_id" class="mt-1 block w-full">
                    <x-slot name="options">
                        <option value="" disabled selected>{{ __('Pilih Tahun Ajaran') }}</option>
                        @foreach ($academicYears as $academicYear)
                            <option value="{{ $academicYear->id }}" @if (old('academic_year_id') == $academicYear->id) selected @endif>
                                {{ $academicYear->year }} - {{ $academicYear->semester }}
                            </option>
                        @endforeach
                    </x-slot>
                </x-select-input>
                <x-input-error :messages="$errors->default->get('academic_year_id')" class="mt-2" />
            </div>


            <div class="mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Batal') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Buka Praktikum') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
