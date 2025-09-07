<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Practicum Details: ') . $practicum->course->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-1">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">
                            Practicum Information
                        </h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <dt class="font-medium text-gray-500">Course</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->course->name }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Academic Year</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->academicYear->year }} -
                                    {{ $practicum->academicYear->semester }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Shift</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->shift->name }}</dd>
                            </div>
                            {{-- [BARU] Menampilkan informasi Dosen & Asisten --}}
                            {{-- <div>
                                <dt class="font-medium text-gray-500">Lecturer</dt>
                                <dd class="mt-1 text-gray-900">{{ $practicum->lecturer->name ?? 'Not assigned' }}</dd>
                            </div>
                            <div>
                                <dt class="font-medium text-gray-500">Assistants</dt>
                                <dd class="mt-1 text-gray-900">
                                    @forelse($practicum->assistants as $assistant)
                                        - {{ $assistant->name }} <br>
                                    @empty
                                        Not assigned
                                    @endforelse
                                </dd>
                            </div> --}}
                        </div>
                        <div class="mt-6 border-t pt-6">
                            {{-- [DIUBAH] Kembali ke halaman "My Practicum" --}}
                            <a href="{{ route('my-practicum.index') }}"
                                class="w-full text-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Back to My Practicums
                            </a>
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg" x-data="{ activeTab: 'jadwal' }">
                        <div class="border-b border-gray-200">
                            <nav class="-mb-px flex space-x-6 overflow-x-auto px-6" aria-label="Tabs">
                                {{-- [DIUBAH] Tab disesuaikan untuk mahasiswa --}}
                                <button @click="activeTab = 'jadwal'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'jadwal' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    Schedule
                                </button>
                                <button @click="activeTab = 'pengumuman'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'pengumuman' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    Announcements
                                </button>
                                <button @click="activeTab = 'tugas'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'tugas' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    Assignments
                                </button>
                                {{-- [BARU] Tab untuk materi/modul --}}
                                <button @click="activeTab = 'materi'"
                                    :class="{ 'border-indigo-500 text-indigo-600': activeTab === 'materi' }"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm text-gray-500 hover:text-gray-700">
                                    Modules
                                </button>
                            </nav>
                        </div>

                        <div class="p-6">
                            {{-- Tab Jadwal --}}
                            <div x-show="activeTab === 'jadwal'">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Practicum Schedule</h3>
                                <div class="overflow-x-auto relative">
                                    <table class="w-full text-sm text-left text-gray-500">
                                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                            <tr>
                                                <th class="py-3 px-6">Meeting</th>
                                                <th class="py-3 px-6">Topic</th>
                                                <th class="py-3 px-6">Date & Time</th>
                                                <th class="py-3 px-6">My Attendance</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($practicum->schedules->sortBy('meeting_number') as $schedule)
                                                <tr class="bg-white border-b hover:bg-gray-50">
                                                    <td class="py-4 px-6 font-medium">{{ $schedule->meeting_number }}
                                                    </td>
                                                    <td class="py-4 px-6">{{ $schedule->topic }}</td>
                                                    <td class="py-4 px-6">
                                                        {{ \Carbon\Carbon::parse($schedule->date)->isoFormat('D MMM Y') }},
                                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                                    </td>
                                                    <td class="py-4 px-6">
                                                        {{-- Logika untuk menampilkan status absensi mahasiswa --}}
                                                        <span class="text-green-600 font-semibold">Present</span>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="py-4 px-6 text-center">No schedule
                                                        available yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Tab Pengumuman --}}
                            <div x-show="activeTab === 'pengumuman'">
                                <h3 class="text-lg">Announcements Content</h3>
                            </div>
                            {{-- Tab Tugas --}}
                            <div x-show="activeTab === 'tugas'">
                                <h3 class="text-lg">Assignments Content</h3>
                            </div>
                            {{-- Tab Materi --}}
                            <div x-show="activeTab === 'materi'">
                                <h3 class="text-lg">Modules & Resources Content</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
