<x-app-layout x-data="{ shift: {}, action: '' }">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Shift') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex flex-col gap-4">
            @can('shift.add')
                <x-primary-button class="self-end" x-data x-on:click="$dispatch('open-modal', 'add-shift')">
                    {{ __('Add Shift') }}
                </x-primary-button>
            @endcan

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="py-3 px-6">
                                    {{ __('Name') }}
                                </th>
                                <th scope="col" class="py-3 px-6 text-right">
                                    {{ __('Action') }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($shifts as $shift)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900 whitespace-nowrap">
                                        {{ $shift->name }}
                                    </th>
                                    <td class="py-4 px-6 flex flex-col items-end">
                                        @can('shift.edit')
                                            <button class="font-medium text-blue-600 hover:underline"
                                                x-on:click.prevent="shift = {{ json_encode($shift->only(['id', 'name'])) }};
                                            action = '{{ route('shift.update', $shift) }}';
                                            $dispatch('open-modal', 'edit-shift');">
                                                {{ __('Edit') }}
                                            </button>
                                        @endcan

                                        @can('shift.delete')
                                            <form action="{{ route('shift.destroy', $shift) }}" method="POST"
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
                                    <td colspan="2" class="py-4 px-6 text-center text-gray-500">
                                        {{ __('No shift data available.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $shifts->links('components.pagination.pagination') }}
                </div>
            </div>
        </div>
    </div>

    @include('shift.partials.add-shift-modal')

    @include('shift.partials.edit-shift-modal')
</x-app-layout>
