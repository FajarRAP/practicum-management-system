<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Student Identity') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Update your identity to enroll practicum.') }}
        </p>
    </header>

    <form method="POST" action="{{ route('student.edit') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
        @csrf
        @method('PUT')
        <div>
            <x-input-label for="student_number" :value="__('Student Number')" />
            <x-text-input id="student_number" name="student_number" type="text" class="mt-1 block w-full"
                :value="old('student_number', $user->student->student_number ?? null)" autofocus autocomplete="student_number" />
            <x-input-error class="mt-2" :messages="$errors->get('student_number')" />
        </div>

        <div>
            <span class="flex gap-2">
                <x-input-label for="study_plan" :value="__('Study Plan')" />
                @if ($user->student->study_plan_path)
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ asset("storage/{$user->student->study_plan_path}") }}">
                        {{ __('View') }}
                    </a>
                @endif
            </span>
            <x-file-input id="study_plan" name="study_plan" type="text" class="mt-1 block" />
            <x-input-error class="mt-2" :messages="$errors->get('study_plan')" />
        </div>

        <div>
            <span class="flex gap-2">

                <x-input-label for="transcript" :value="__('Transcript')" />
                @if ($user->student->transcript_path)
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ asset("storage/{$user->student->transcript_path}") }}">
                        {{ __('View') }}
                    </a>
                @endif
            </span>
            <x-file-input id="transcript" name="transcript" type="text" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('transcript')" />
        </div>

        <div>
            <span class="flex gap-2">
                <x-input-label for="photo" :value="__('Photo')" />
                @if ($user->student->photo_path)
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        href="{{ asset("storage/{$user->student->photo_path}") }}">
                        {{ __('View') }}
                    </a>
                @endif
            </span>
            <x-file-input id="photo" name="photo" type="text" class="mt-1 block w-full" />
            <x-input-error class="mt-2" :messages="$errors->get('photo')" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </div>
    </form>

</section>
