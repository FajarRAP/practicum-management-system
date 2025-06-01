@extends('layouts.archive')

@section('add-data-button')
    @hasrole('assistant')
        <x-primary-button class="self-end" x-data @click.prevent="$dispatch('open-modal', 'add-archive')">
            {{ __('Add Archive') }}
        </x-primary-button>
    @endhasrole
@endsection

@section('add-data-modal')
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
                    <x-text-input id="title" name="title" class="mt-1 block w-3/4" placeholder="{{ __('Title') }}" />
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
@endsection
