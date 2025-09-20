<x-modal name="view-record-modal">
    <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('My Record') }}: {{ __('Meeting') }} <span x-text="myRecord.schedule?.meeting_number || ''"></span>
        </h2>

        <div class="mt-6 space-y-4">
            <dl class="divide-y divide-gray-200">
                <div class="py-3 flex justify-between text-sm">
                    <dt class="font-medium text-gray-600">{{ __('Attendance Status') }}</dt>
                    <dd class="px-2 py-1 font-semibold leading-tight rounded-full text-xs" x-text="myRecord.status"
                        x-bind:class="{
                            'text-green-700 bg-green-100': myRecord.status === 'PRESENT',
                            'text-blue-700 bg-blue-100': myRecord.status === 'SICK' || myRecord
                                .status === 'EXCUSED',
                            'text-red-700 bg-red-100': myRecord.status === 'ABSENT',
                            'text-gray-700 bg-gray-100': !myRecord.status
                        }">
                    </dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="font-medium text-gray-600">{{ __('Participation Score') }}</dt>
                    <dd class="text-gray-900" x-text="myRecord.participation_score"></dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="font-medium text-gray-600">{{ __('Creativity Score') }}</dt>
                    <dd class="text-gray-900" x-text="myRecord.creativity_score"></dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="font-medium text-gray-600">{{ __('Report Score') }}</dt>
                    <dd class="text-gray-900" x-text="myRecord.report_score"></dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="font-medium text-gray-600">{{ __('Active Score') }}</dt>
                    <dd class="text-gray-900" x-text="myRecord.active_score"></dd>
                </div>
                <div class="py-3 flex justify-between text-sm">
                    <dt class="font-medium text-gray-600">{{ __('Module Score') }}</dt>
                    <dd class="text-gray-900" x-text="myRecord.module_score"></dd>
                </div>
            </dl>
        </div>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Close') }}
            </x-secondary-button>
        </div>
    </div>
</x-modal>
