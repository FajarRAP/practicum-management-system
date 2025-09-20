<x-app-layout x-data="{ academicYear: {}, action: '' }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Academic Year') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            @can('academic_year.add')
                <x-primary-button class="self-end" x-data x-on:click="$dispatch('open-modal', 'add-academic-year')">
                    {{ __('Add Academic Year') }}
                </x-primary-button>
            @endcan

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Year') }}
                                </th>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Semester') }}
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
                            @forelse ($academicYears as $academicYear)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $academicYear->year }}
                                    </th>
                                    <td class="py-4 px-6">
                                        {{ $academicYear->semester }}
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($academicYear->status == 'ACTIVE')
                                            <span
                                                class="px-4 py-1.5 font-semibold leading-tight text-green-700 bg-green-100 rounded-full">
                                                {{ __('Active') }}
                                            </span>
                                        @elseif ($academicYear->status == 'FINISHED')
                                            <span
                                                class="px-4 py-1.5 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full">
                                                {{ __('Finished') }}
                                            </span>
                                        @else
                                            <span
                                                class="px-4 py-1.5 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full">
                                                {{ __('Draft') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6">
                                        @can('academic_year.edit')
                                            <button class="font-medium text-blue-600 hover:underline"
                                                x-on:click.prevent="
                                                academicYear = {{ json_encode($academicYear->only(['id', 'year', 'semester', 'status'])) }};
                                                action = '{{ route('academic-year.update', $academicYear) }}';
                                                $dispatch('open-modal', 'edit-academic-year');">
                                                {{ __('Edit') }}
                                            </button>
                                        @endcan

                                        @can('academic_year.delete')
                                            <form action="{{ route('academic-year.destroy', $academicYear) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('Are you sure you want to delete this data?') }}');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="font-medium text-red-600 hover:underline">{{ __('Delete') }}</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-4 px-6 text-center text-gray-500">
                                        {{ __('There is no academic years data.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $academicYears->links('components.pagination.pagination') }}
                </div>
            </div>
        </div>
    </div>

    @include('academic-year.partials.add-academic-year-modal')

    @include('academic-year.partials.edit-academic-year-modal')
</x-app-layout>
