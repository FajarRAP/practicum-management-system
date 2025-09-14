<x-modal name="reject-schedule-modal" :show="$errors->rejectSchedule->isNotEmpty()" focusable>
    <form method="POST" x-bind:action="action" class="p-6">
        @csrf
        @method('PATCH')

        <h2 class="text-lg font-medium text-gray-900">{{ __('Reject Schedule') }}</h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('You can provide an optional reason for rejecting this schedule request.') }}
        </p>

        <div class="mt-6">
            <x-input-label for="rejection_reason" value="{{ __('Reason') }}" class="sr-only" />
            <x-textarea id="rejection_reason" name="rejection_reason" class="mt-1 block w-full"
                placeholder="{{ __('Reason for rejection...') }}"></x-textarea>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
            <x-danger-button class="ms-3">{{ __('Reject Schedule') }}</x-danger-button>
        </div>
    </form>
</x-modal>
