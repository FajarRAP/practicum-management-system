<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Assignment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            @hasrole('assistant')
                <x-primary-button class="self-end" x-data @click="$dispatch('open-modal', 'add-assignment')">
                    {{ __('Add Assignment') }}
                </x-primary-button>
            @endhasrole

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg ">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 border-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Title') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Due Date') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Student') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($assignments as $assignment)
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $assignment->title }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ \Carbon\Carbon::parse($assignment->due_date)->format('l, d M Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a href="{{ route('assignment-submission.index', $assignment) }}"
                                            class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            {{ __('View') }}
                                        </a>
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

    @hasrole('assistant')
        <x-modal name="add-assignment" :show="$errors->addAssignment->isNotEmpty()" focusable>
            <form method="POST" action="{{ route('assignment.store') }}" class="p-6 flex flex-col gap-4">
                @csrf
                @method('POST')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Add Assignment') }}
                </h2>

                <div>
                    <x-input-label for="announcement" value="{{ __('Announcement') }}" />
                    <x-select-input id="announcement" name="announcement" class="mt-1 block w-3/4">
                        <x-slot name="options">
                            <option value="" disabled selected>{{ __('Select') . ' ' . __('Announcement') }}</option>
                            @foreach ($announcements as $announcement)
                                <option value="{{ $announcement->id }}">
                                    @if ($announcement->schedule->shift)
                                        {{ $announcement->schedule->course->name . ' - ' . $announcement->schedule->shift . ' - ' . $announcement->activity }}
                                    @else
                                        {{ $announcement->schedule->course->name . ' - ' . $announcement->activity }}
                                    @endif
                                </option>
                            @endforeach
                        </x-slot>
                    </x-select-input>
                    <x-input-error :messages="$errors->addAssignment->get('announcement')" />
                </div>
                <div>
                    <x-input-label for="Title" value="{{ __('Title') }}" />
                    <x-text-input id="title" name="title" type="text" class="mt-1 block w-3/4"
                        placeholder="{{ __('Title') }}" />
                    <x-input-error :messages="$errors->addAssignment->get('title')" />
                </div>
                <div>
                    <x-input-label for="due_date" value="{{ __('Due Date') }}" />
                    <x-text-input id="due_date" name="due_date" type="datetime-local" class="mt-1 block w-3/4"
                        placeholder="{{ __('Due Date') }}" />
                    <x-input-error :messages="$errors->addAssignment->get('due_date')" />
                </div>

                <div class="flex justify-end">
                    <x-secondary-button @click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>

                    <x-primary-button class="ms-3">
                        {{ __('Add Assignment') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    @endhasrole
</x-app-layout>
