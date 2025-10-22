<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('questionnaire.index') }}" class="text-gray-400 hover:text-gray-600">
                <x-svgs.arrow-left class="text-gray-400 hover:text-gray-600" />
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Results for: ') . $questionnaire->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-600 mb-2">{{ __('Total Responses') }}: {{ $totalResponses }}</p>
                <div class="space-y-10">
                    @foreach ($results as $result)
                        <div class="border-t pt-6">
                            <h3 class="text-base font-semibold text-gray-900">
                                {{ $result['question']->content }}
                            </h3>

                            @if ($result['question']->type === 'radio' || $result['question']->type === 'checkbox')
                                <div class="mt-4 space-y-3">
                                    @forelse ($result['answers'] as $option => $count)
                                        <div class="flex items-center">
                                            <span class="w-1/3 text-sm text-gray-700">{{ $option }}</span>
                                            <div class="w-2/3 bg-gray-200 rounded-full h-5">
                                                <div class="bg-indigo-600 h-5 rounded-full text-xs font-medium text-blue-100 text-center leading-5"
                                                    style="width: {{ $totalResponses > 0 ? ($count / $totalResponses) * 100 : 0 }}%">
                                                    {{ $count }}
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-gray-500 italic">No responses for this question yet.</p>
                                    @endforelse
                                </div>
                            @else
                                <div class="mt-4 space-y-2 max-h-60 overflow-y-auto">
                                    @forelse ($result['answers'] as $answer)
                                        <p class="text-sm text-gray-800 bg-gray-50 p-2 border rounded-md">
                                            {{ $answer }}</p>
                                    @empty
                                        <p class="text-sm text-gray-500 italic">No responses for this question yet.</p>
                                    @endforelse
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
