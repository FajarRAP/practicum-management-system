<x-modal name="submit-assignment-modal" :show="$errors->addSubmission->isNotEmpty()" focusable>
    <form method="POST" x-bind:action="action" class="p-6" enctype="multipart/form-data">
        @csrf

        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Submit Assignment') }}: <span x-text="assignment.title"></span>
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Make sure your file is correct before submitting. You cannot change the file after submission.') }}
        </p>

        <input type="hidden" name="assignment_id" x-bind:value="assignment.id">

        <div class="mt-6 space-y-4">
            <div>
                <x-input-label for="submission_file" :value="__('Your Assignment File (*.pdf, *.zip, *.rar)')" />
                <x-file-input id="submission_file" name="submission_file" class="mt-1 block w-full" required />
                <x-input-error :messages="$errors->addSubmission->get('submission_file')" class="mt-2" />
            </div>
            {{-- <div>
                <x-input-label for="comments" value="{{ __('Comments (Optional)') }}" />
                <x-textarea id="comments" name="comments" rows="3" class="mt-1 block w-full"
                    placeholder="Add any comments for the assistant...">{{ old('comments') }}</x-textarea>
                <x-input-error :messages="$errors->addSubmission->get('comments')" class="mt-2" />
            </div> --}}
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-primary-button class="ms-3">
                {{ __('Submit') }}
            </x-primary-button>
        </div>
    </form>
</x-modal>
