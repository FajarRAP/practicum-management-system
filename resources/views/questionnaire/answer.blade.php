<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center space-x-4">
            <a href="{{ route('questionnaire.index') }}">
                <x-svgs.arrow-left class="text-gray-400 hover:text-gray-600" />
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('My Answer') }}
                </h2>
                <p class="text-sm text-gray-500 mt-1">
                    {{ __('For Questionnaire') }}: <span class="font-semibold">{{ $questionnaire->title }}</span>
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="py-3 px-6">{{ __('Question') }}</th>
                                <th class="py-3 px-6">{{ __('Answer') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($answers as $answer)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900">
                                        {{ $answer->question->content }}
                                    </th>
                                    <td class="py-4 px-6">
                                        {{ $answer->content }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-6 px-6 text-center text-gray-500 italic">
                                        {{ __('You have not submitted this questionnaire yet.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
