<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('questionnaire.index') }}">
                <x-svgs.arrow-left class="text-gray-400 hover:text-gray-600" />
            </a>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $questionnaire->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('answer.store', $questionnaire) }}" method="POST">
                @csrf
                <input type="hidden" name="questionnaire_id" value="{{ $questionnaire->id }}">

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-600 mb-8">{{ $questionnaire->description }}</p>

                    <div class="space-y-8">
                        @foreach ($questionnaire->questions as $question)
                            <fieldset class="border-t pt-6">
                                <legend class="text-base font-medium text-gray-900">
                                    {{ $loop->iteration }}. {{ $question->content }}
                                </legend>
                                <div class="mt-4 space-y-1">
                                    @if ($question->type === 'radio')
                                        <input type="hidden" name="answers[{{ $question->id }}]" value="">
                                        @foreach (json_decode($question->options) as $option)
                                            <label class="flex items-center">
                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                    value="{{ $option }}"
                                                    class="text-indigo-600 focus:ring-indigo-500"
                                                    {{ old('answers.' . $question->id) == $option ? 'checked' : '' }}
                                                    required>
                                                <span class="ms-3 text-sm text-gray-600">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    @elseif ($question->type === 'checkbox')
                                        @foreach (json_decode($question->options) as $option)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="answers[{{ $question->id }}][]"
                                                    value="{{ $option }}"
                                                    class="rounded text-indigo-600 focus:ring-indigo-500"
                                                    {{ in_array($option, old('answers.' . $question->id, [])) ? 'checked' : '' }}>
                                                <span class="ms-3 text-sm text-gray-600">{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    @elseif ($question->type === 'text')
                                        <x-textarea name="answers[{{ $question->id }}]" rows="3" class="w-full"
                                            placeholder="Jawaban Anda..." required>
                                            {{ old('answers.' . $question->id) }}</x-textarea>
                                    @endif

                                    <x-input-error :messages="$errors->get('answers.' . $question->id) ? 'Must be filled' : null" />
                                </div>
                            </fieldset>
                        @endforeach
                    </div>

                    <div class="mt-8 border-t pt-6 flex justify-end">
                        <x-primary-button>
                            {{ __('Submit Questionnaire') }}
                        </x-primary-button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
