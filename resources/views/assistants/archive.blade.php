<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Archives') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            @hasrole('assistant')
                <x-primary-button class="self-end" x-data @click.prevent="$dispatch('open-modal', 'add-archive')">
                    {{ __('Add Archive') }}
                </x-primary-button>
            @endhasrole

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 border-gray-300">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('Name') }}
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    {{ __('File') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($archives as $archive)
                                <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        {{ $archive->title }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                            href="{{ asset("storage/$archive->file_path") }}">
                                            {{ __('View') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @hasrole('assistant')
        <x-modal name="add-archive" :show="$errors->addArchive->isNotEmpty()" focusable>
            <form method="POST" action="{{ route('archive.store') }}" class="p-6 flex flex-col gap-4"
                enctype="multipart/form-data">
                @csrf
                @method('POST')

                <h2 class="text-lg font-medium text-gray-900">
                    {{ __('Add Archive') }}
                </h2>

                <div>
                    <x-input-label for="title" value="{{ __('Title') }}" />
                    <x-text-input id="title" name="title" class="mt-1 block w-3/4"
                        placeholder="{{ __('Title') }}" />
                    <x-input-error :messages="$errors->addArchive->get('title')" />
                </div>
                <div>
                    <x-input-label for="file" value="{{ __('Archive File') }}" />
                    <x-file-input id="file" name="file" class="mt-1 block w-3/4" />
                    <x-input-error :messages="$errors->addArchive->get('file')" />
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
    @endhasrole
</x-app-layout>
