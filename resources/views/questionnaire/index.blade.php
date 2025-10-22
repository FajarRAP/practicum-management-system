<x-app-layout x-data="{ questionnaire: {}, action: '' }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Questionnaire Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            @can('questionnaire.add')
                <x-primary-button class="self-end" x-data x-on:click="$dispatch('open-modal', 'add-questionnaire')">
                    {{ __('Create New Questionnaire') }}
                </x-primary-button>
            @endcan

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg min-h-96">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="py-3 px-6">Title</th>
                                <th class="py-3 px-6">Status</th>
                                @can('questionnaire.view-results')
                                    <th class="py-3 px-6">Respondents</th>
                                @endcan
                                <th class="py-3 px-6">Created At</th>
                                <th class="py-3 px-6 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($questionnaires as $questionnaire)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900">
                                        {{ $questionnaire->title }}
                                    </th>
                                    <td class="py-4 px-6">
                                        @if ($questionnaire->is_active)
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">Active</span>
                                        @else
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full text-xs">Inactive</span>
                                        @endif
                                    </td>
                                    @can('questionnaire.view-results')
                                        <td class="py-4 px-6">
                                            {{ $questionnaire->respondents_count }}
                                        </td>
                                    @endcan
                                    <td class="py-4 px-6">
                                        {{ $questionnaire->created_at->isoFormat('D MMM Y') }}
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <x-dropdown align="right" width="48">
                                            <x-slot name="trigger">
                                                <button class="text-gray-500 hover:text-gray-700 focus:outline-none">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
                                                    </svg>
                                                </button>
                                            </x-slot>
                                            <x-slot name="content">
                                                @can('questionnaire.answer')
                                                    <x-dropdown-link :href="route('answer.index', $questionnaire)">
                                                        {{ __('Show My Answers') }}
                                                    </x-dropdown-link>
                                                    <x-dropdown-link :href="route('answer.create', $questionnaire)">
                                                        {{ __('Fill Questionnaire') }}
                                                    </x-dropdown-link>
                                                @endcan
                                                @can('questionnaire.view-results')
                                                    <x-dropdown-link :href="route('questionnaire.show', $questionnaire)">
                                                        {{ __('View Results') }}
                                                    </x-dropdown-link>
                                                @endcan
                                                @can('question.add')
                                                    <x-dropdown-link :href="route('question.index', $questionnaire)">
                                                        {{ __('Manage Questions') }}
                                                    </x-dropdown-link>
                                                @endcan
                                                @can('questionnaire.edit')
                                                    <x-dropdown-link href="#"
                                                        x-on:click.prevent="
                                                    questionnaire = {{ json_encode($questionnaire) }};
                                                    action = '{{ route('questionnaire.update', $questionnaire) }}';
                                                    $dispatch('open-modal', 'edit-questionnaire');">
                                                        {{ __('Edit Details') }}
                                                    </x-dropdown-link>
                                                @endcan
                                                @can('questionnaire.delete')
                                                    <div class="border-t border-gray-100"></div>
                                                    <form action="{{ route('questionnaire.destroy', $questionnaire) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <x-dropdown-link href="#"
                                                            onclick="
                                                            event.preventDefault(); 
                                                            if (confirm('{{ __('Are you sure you want to delete this data?') }}')) {
                                                                this.closest('form').submit();
                                                            }"
                                                            class="text-red-600">
                                                            {{ __('Delete') }}
                                                        </x-dropdown-link>
                                                    </form>
                                                @endcan
                                            </x-slot>
                                        </x-dropdown>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 px-6 text-center text-gray-500 italic">
                                        No questionnaires have been created yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($questionnaires->hasPages())
                    <div class="p-6">
                        {{ $questionnaires->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    @can('questionnaire.add')
        @include('questionnaire.partials.add-questionnaire-modal')
    @endcan

    @can('questionnaire.edit')
        @include('questionnaire.partials.edit-questionnaire-modal')
    @endcan
</x-app-layout>
