<x-app-layout x-data="{ practicum: {}, action: '' }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Practicums') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            @can('practicum.add')
                <x-primary-button class="self-end" x-data x-on:click="$dispatch('open-modal', 'add-practicum')">
                    {{ __('Add Practicum') }}
                </x-primary-button>
            @endcan

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Course') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Academic Year') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Semester') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Shift') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Status') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($practicums as $practicum)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="py-4 px-6">
                                        <div class="font-medium text-gray-900">{{ $practicum->course->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $practicum->course->code }}</div>
                                    </th>
                                    <td class="py-4 px-6">
                                        {{ $practicum->academicYear->year }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ $practicum->academicYear->semester }}
                                    </td>
                                    <td class="py-4 px-6">
                                        {{ $practicum->shift->name }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($practicum->academicYear->status == 'ACTIVE')
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full text-xs">{{ __('Active') }}</span>
                                        @elseif ($practicum->academicYear->status == 'FINISHED')
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full text-xs">{{ __('Finished') }}</span>
                                        @else
                                            <span
                                                class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full text-xs">{{ __('Draft') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @can('practicum.view')
                                            <a href="{{ route('practicum.show', $practicum) }}"
                                                class="font-medium text-indigo-600 hover:underline">
                                                {{ __('View') }}
                                            </a>
                                        @endcan
                                        @can('practicum.delete')
                                            <form action="{{ route('practicum.destroy', $practicum) }}" method="POST"
                                                onsubmit="return confirm('Are you sure you want to delete this practicum?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:underline">
                                                    {{ __('Delete') }}
                                                </button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                                        {{ __('No practicum data available.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $practicums->links('components.pagination.pagination') }}
                </div>
            </div>
        </div>
    </div>

    @can('practicum.add')
        @include('practicum.partials.add-practicum-modal')
    @endcan
</x-app-layout>
