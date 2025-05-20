<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assignment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 border-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Course') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Shift') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Title') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Due Date') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Submission') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignments as $assignment)
                                @php
                                    $isSubmitted = auth()
                                        ->user()
                                        ->submissions->contains('assignment_id', $assignment->id);
                                    $isAttended = auth()
                                        ->user()
                                        ->attendances->contains('announcement_id', $assignment->announcement->id);

                                @endphp
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $assignment->announcement->schedule->course->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $assignment->announcement->schedule->shift->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $assignment->title }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ Carbon\Carbon::parse($assignment->due_date)->format('l, d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if (!$isAttended)
                                            <p
                                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Not Attended Yet') }}
                                            </p>
                                        @elseif ($isAttended && !$isSubmitted)
                                            <a href=""
                                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                                x-data
                                                @click.prevent="$dispatch('open-modal', 'submit-assignment-{{ $assignment->id }}')">
                                                {{ __('Not Submitted Yet') }}
                                            </a>
                                            <x-modal name="submit-assignment-{{ $assignment->id }}" :show="$errors->submitAssignment->isNotEmpty()"
                                                focusable>
                                                <form method="POST"
                                                    action="{{ route('assignment-submission.store', $assignment) }}"
                                                    class="p-6 flex flex-col gap-4" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('POST')

                                                    <h2 class="text-lg font-medium text-gray-900">
                                                        {{ __('Submit Assignment') }}
                                                    </h2>

                                                    <div>
                                                        <x-input-label for="file"
                                                            value="{{ __('Submission File') }}" />
                                                        <x-file-input id="file" name="file"
                                                            class="mt-1 block w-3/4"
                                                            placeholder="{{ __('Submission File') }}" />
                                                        <x-input-error :messages="$errors->submitAssignment->get('file')" />
                                                    </div>

                                                    <div class="flex justify-end">
                                                        <x-secondary-button @click="$dispatch('close')">
                                                            {{ __('Cancel') }}
                                                        </x-secondary-button>

                                                        <x-primary-button class="ms-3">
                                                            {{ __('Submit') }}
                                                        </x-primary-button>
                                                    </div>
                                                </form>
                                            </x-modal>
                                        @else
                                            <p
                                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Submitted') }}
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $assignments->links('components.pagination.pagination') }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
