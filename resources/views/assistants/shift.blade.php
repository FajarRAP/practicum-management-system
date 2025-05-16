<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Shift') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            <x-primary-button class="self-end" x-data @click="$dispatch('open-modal', 'add-shift')">
                {{ __('Add Shift') }}
            </x-primary-button>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 min-h-96 border-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Course') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Day') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Time') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Shift') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Registered Student') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- @foreach ($courses as $course)
                                    <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                        <th scope="row"
                                            class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                            {{ $loop->index + 1 }}
                                        </th>
                                        <td class="px-6 py-4">
                                            {{ $course->course_name }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $course->credit_hour }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-text-input type="checkbox" class="checked:text-indigo-500"
                                                :disabled="true" :checked="$course->is_has_practicum" />
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-text-input type="checkbox" class="checked:text-indigo-500"
                                                :disabled="true" :checked="$course->is_online" />
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-text-input type="checkbox" class="checked:text-indigo-500"
                                                :disabled="true" :checked="$course->is_even_semester" />
                                        </td>
                                        <td class="px-6 py-4">
                                            <x-danger-button x-data
                                                x-on:click.prevent="$dispatch('open-modal', 'delete-course-{{ $course->id }}')">
                                                {{ __('Delete') }}
                                            </x-danger-button>
                                            <x-delete-data-modal :action="route('course.destroy', $course)"
                                                name="delete-course-{{ $course->id }}" />
                                        </td>
                                    </tr>
                                @endforeach --}}
                        </tbody>
                    </table>
                    {{-- {{ $courses->links('components.pagination.pagination') }} --}}
                </div>
            </div>
        </div>
    </div>

    <x-modal name="add-shift" :show="$errors->addCourse->isNotEmpty()" focusable>
        <form method="POST" action="{{ route('shift.store') }}" class="p-6 flex flex-col gap-4">
            @csrf
            @method('POST')

            <h2 class="text-lg font-medium text-gray-900">
                {{ __('Add Shift') }}
            </h2>

            <div>
                <x-input-label for="course" value="{{ __('Course') }}" />
                <x-text-input id="course" name="course" type="text" class="mt-1 block w-3/4"
                    placeholder="{{ __('Course') }}" />
                <x-input-error :messages="$errors->addCourse->get('course')" />
            </div>
            <div>
                <x-input-label for="day" value="{{ __('Day') }}" />
                <x-text-input id="day" name="day" type="text" class="mt-1 block w-3/4"
                    placeholder="{{ __('Day') }}" />
                <x-input-error :messages="$errors->addCourse->get('day')" />
            </div>
            <div>
                <x-input-label for="shift" value="{{ __('Shift') }}" />
                <x-text-input id="shift" name="shift" type="text" class="mt-1 block w-3/4"
                    placeholder="{{ __('Shift') }}" />
                <x-input-error :messages="$errors->addCourse->get('Shift')" />
            </div>
            <div>
                <x-input-label for="time" value="{{ __('Time') }}" />
                <x-text-input id="time" name="time" type="text" class="mt-1 block w-3/4"
                    placeholder="{{ __('Time') }}" />
                <x-input-error :messages="$errors->addCourse->get('time')" />
            </div>

            <div class="flex justify-end">
                <x-secondary-button @click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-primary-button class="ms-3">
                    {{ __('Add Course') }}
                </x-primary-button>
            </div>
        </form>
    </x-modal>
</x-app-layout>
