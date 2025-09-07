<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Praktikum: ') . $practicum->course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                {{-- Di sini Anda akan @include partial untuk manajemen enrollment --}}
                                {{-- Contoh: @include('practicum.partials.enrollments', ['practicum' => $practicum]) --}}
                                @include('practicum.partials.enrollments', ['practicum' => $practicum])
                            </div>

                            <div x-show="activeTab === 'jadwal'">
                                {{-- Di sini Anda akan @include partial untuk manajemen schedule dan attendance --}}
                                <h3 class="text-lg">Manajemen Jadwal & Absensi</h3>
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
        </div>
    </div>
</x-app-layout>
