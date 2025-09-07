<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Praktikum: ') . $practicum->course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ editSchedule: {}, action: '' }">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Informasi Praktikum
                        </h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <dt class="font-medium text-gray-500">Mata Kuliah</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->course->name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Tahun Ajaran</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->academicYear->year }} -
                                    {{ $practicum->academicYear->semester }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Status</dt>
                                <dd class="mt-1">
                                    @if ($practicum->academicYear->status == 'ACTIVE')
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">Aktif</span>
                                    @else
                                        <span
                                            class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full text-xs">Tidak
                                            Aktif</span>
                                    @endif
                                </dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Jumlah Peserta</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->enrollments->count() }}</dd>
                            </div>
                        </div>
                        <div class="mt-6 border-t pt-6">
                            <a href="{{ route('practicum.index') }}"
                                class="w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ activeTab: 'peserta' }">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-6 overflow-x-auto px-6" aria-label="Tabs">
                                {{-- Tombol Tab --}}
                                <button @click="activeTab = 'peserta'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'peserta' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    Peserta
                                </button>
                                <button @click="activeTab = 'jadwal'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'jadwal' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    Jadwal & Absensi
                                </button>
                                <button @click="activeTab = 'pengumuman'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'pengumuman' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    Pengumuman
                                </button>
                                <button @click="activeTab = 'tugas'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'tugas' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    Tugas & Penilaian
                                </button>
                            </nav>
                        </div>

                        <div class="p-6">
                            <div x-show="activeTab === 'peserta'">
                                @include('practicum.partials.enrollments', ['practicum' => $practicum])
                            </div>

                            <div x-show="activeTab === 'jadwal'">
                                {{-- Di sini Anda akan @include partial untuk manajemen schedule dan attendance --}}
                                @include('practicum.partials.schedule', ['practicum' => $practicum])
                            </div>

                            <div x-show="activeTab === 'pengumuman'">
                                {{-- Di sini Anda akan @include partial untuk manajemen announcement --}}
                                <h3 class="text-lg">Manajemen Pengumuman</h3>
                            </div>

                            <div x-show="activeTab === 'tugas'">
                                {{-- Di sini Anda akan @include partial untuk manajemen assignment dan assessment --}}
                                <h3 class="text-lg">Manajemen Tugas & Penilaian</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Student Status Modal --}}
            {{-- <x-modal name="reject-enrollment" focusable>
                <form method="POST" x-bind:action="action" class="p-6">
                    @csrf
                    @method('PATCH')

                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Reject Enrollment') }}
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Please provide a reason for rejecting this enrollment. The student will be notified.') }}
                    </p>

                    <div class="mt-6">
                        <x-input-label for="rejection_reason" value="{{ __('Reason') }}" class="sr-only" />
                        <x-textarea id="rejection_reason" name="rejection_reason" class="mt-1 block w-full"
                            placeholder="{{ __('Reason for rejection...') }}" required></x-textarea>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-danger-button class="ms-3">
                            {{ __('Reject Enrollment') }}
                        </x-danger-button>
                    </div>
                </form>
            </x-modal> --}}

            {{-- [BARU] Modal Tambah Jadwal --}}
            <x-modal name="add-schedule-modal" :show="$errors->addSchedule->isNotEmpty()" focusable>
                <form method="POST" action="{{ route('schedule.store') }}" class="p-6">
                    @csrf
                    <input type="hidden" name="practicum_id" value="{{ $practicum->id }}">

                    <h2 class="text-lg font-medium text-gray-900">{{ __('Add New Schedule') }}</h2>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="meeting_number" value="{{ __('Meeting Number') }}" />
                            <x-text-input id="meeting_number" name="meeting_number" type="number"
                                class="mt-1 block w-full" :value="old('meeting_number')" required />
                            <x-input-error :messages="$errors->addSchedule->get('meeting_number')" />
                        </div>
                        <div>
                            <x-input-label for="date" value="{{ __('Date') }}" />
                            <x-text-input id="date" name="date" type="date" class="mt-1 block w-full"
                                :value="old('date')" required />
                            <x-input-error :messages="$errors->addSchedule->get('date')" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="topic" value="{{ __('Topic') }}" />
                            <x-text-input id="topic" name="topic" type="text" class="mt-1 block w-full"
                                :value="old('topic')" required />
                            <x-input-error :messages="$errors->addSchedule->get('topic')" />
                        </div>
                        <div>
                            <x-input-label for="start_time" value="{{ __('Start Time') }}" />
                            <x-text-input id="start_time" name="start_time" type="time" class="mt-1 block w-full"
                                :value="old('start_time')" required />
                            <x-input-error :messages="$errors->addSchedule->get('start_time')" />
                        </div>
                        <div>
                            <x-input-label for="end_time" value="{{ __('End Time') }}" />
                            <x-text-input id="end_time" name="end_time" type="time" class="mt-1 block w-full"
                                :value="old('end_time')" required />
                            <x-input-error :messages="$errors->addSchedule->get('end_time')" />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="location" value="{{ __('Location') }}" />
                            <x-text-input id="location" name="location" type="text" class="mt-1 block w-full"
                                placeholder="e.g. Lab 1, Online" :value="old('location')" />
                            <x-input-error :messages="$errors->addSchedule->get('location')" />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">{{ __('Cancel') }}</x-secondary-button>
                        <x-primary-button class="ms-3">{{ __('Save Schedule') }}</x-primary-button>
                    </div>
                </form>
            </x-modal>

            {{-- [BARU] Modal Edit Jadwal --}}
            <x-modal name="edit-schedule-modal" :show="$errors->updateSchedule->isNotEmpty()" focusable>
                <form method="POST" x-bind:action="action" class="p-6">
                    @csrf
                    @method('PUT')
                    <h2 class="text-lg font-medium text-gray-900">{{ __('Edit Schedule') }}</h2>
                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="edit_meeting_number" value="{{ __('Meeting Number') }}" />
                            <x-text-input id="edit_meeting_number" name="meeting_number" type="number"
                                class="mt-1 block w-full" x-model="editSchedule.meeting_number" required />
                        </div>
                        <div>
                            <x-input-label for="edit_date" value="{{ __('Date') }}" />
                            <x-text-input id="edit_date" name="date" type="date" class="mt-1 block w-full"
                                x-model="editSchedule.date" required />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="edit_topic" value="{{ __('Topic') }}" />
                            <x-text-input id="edit_topic" name="topic" type="text" class="mt-1 block w-full"
                                x-model="editSchedule.topic" required />
                        </div>
                        <div>
                            <x-input-label for="edit_start_time" value="{{ __('Start Time') }}" />
                            <x-text-input id="edit_start_time" name="start_time" type="time"
                                class="mt-1 block w-full" x-model="editSchedule.start_time" required />
                        </div>
                        <div>
                            <x-input-label for="edit_end_time" value="{{ __('End Time') }}" />
                            <x-text-input id="edit_end_time" name="end_time" type="time"
                                class="mt-1 block w-full" x-model="editSchedule.end_time" required />
                        </div>
                        <div class="col-span-2">
                            <x-input-label for="edit_location" value="{{ __('Location') }}" />
                            <x-text-input id="edit_location" name="location" type="text"
                                class="mt-1 block w-full" x-model="editSchedule.location" />
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="console.log(action)">{{ __('Cancel') }}</x-secondary-button>
                        <x-primary-button class="ms-3">{{ __('Save Changes') }}</x-primary-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</x-app-layout>
