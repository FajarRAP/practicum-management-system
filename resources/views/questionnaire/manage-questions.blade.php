<x-app-layout x-data="{ questionToEdit: {}, action: '' }">
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('questionnaire.index') }}" class="text-gray-400 hover:text-gray-600">
                <x-svgs.arrow-left class="text-gray-400 hover:text-gray-600" />
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Manage Questions') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    For Questionnaire: <span class="font-semibold">{{ $questionnaire->title }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <x-primary-button x-on:click="$dispatch('open-modal', 'add-question-modal')">
                    {{ __('Add New Question') }}
                </x-primary-button>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <ul class="divide-y divide-gray-200">
                    @forelse ($questionnaire->questions as $question)
                        <li class="p-6 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $question->content }}</p>
                                <span
                                    class="mt-1 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    {{ Str::ucfirst($question->type) }}
                                </span>
                            </div>
                            <div class="flex space-x-4">
                                @can('question.edit')
                                    <button class="font-medium text-blue-600 hover:underline text-sm"
                                        x-on:click.prevent="
                                    questionToEdit = {{ json_encode($question) }};
                                    action = '{{ route('question.update', $question) }}';
                                    $dispatch('open-modal', 'edit-question-modal');">
                                        Edit
                                    </button>
                                @endcan

                                @can('question.delete')
                                    <form action="{{ route('question.destroy', $question) }}" method="POST"
                                        onsubmit="return confirm('{{ __('Are you sure you want to delete this data?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="font-medium text-red-600 hover:underline text-sm">Delete</button>
                                    </form>
                                @endcan
                            </div>
                        </li>
                    @empty
                        <li class="p-6 text-center text-gray-500 italic">
                            No questions have been added to this questionnaire yet.
                        </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>

    @can('question.add')
        @include('questionnaire.partials.add-question-modal')
    @endcan

    @can('question.edit')
        @include('questionnaire.partials.edit-question-modal')
    @endcan
</x-app-layout>
