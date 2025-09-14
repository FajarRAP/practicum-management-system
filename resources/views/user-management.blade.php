<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('User Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ userToEdit: {}, action: '', allRoles: {{ json_encode($roles) }} }">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b">
                    {{-- Di sini Anda bisa menambahkan form search atau filter --}}
                </div>
                <div class="relative overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="py-3 px-6">{{ __('Name') }}</th>
                                <th class="py-3 px-6">{{ __('Email') }}</th>
                                <th class="py-3 px-6">{{ __('Roles') }}</th>
                                <th class="py-3 px-6">{{ __('Verified At') }}</th>
                                <th class="py-3 px-6 text-right">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <th scope="row" class="py-4 px-6 font-medium text-gray-900">
                                        <div>{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $user->identity_number }}</div>
                                    </th>
                                    <td class="py-4 px-6">{{ $user->email }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($user->getRoleNames() as $roleName)
                                                <span
                                                    class="px-2 py-1 font-semibold leading-tight text-indigo-700 bg-indigo-100 rounded-full text-xs">{{ $roleName }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        @if ($user->email_verified_at)
                                            <span
                                                class="text-green-600 font-semibold text-xs">{{ $user->email_verified_at->isoFormat('D MMM Y') }}</span>
                                        @else
                                            <span
                                                class="text-yellow-600 font-semibold text-xs">{{ __('Not Verified') }}</span>
                                        @endif
                                    </td>
                                    <td class="py-4 px-6 text-right">
                                        <button class="font-medium text-blue-600 hover:underline"
                                            x-on:click.prevent="
                                            userToEdit = {{ json_encode($user->load('roles')) }};
                                            action = '{{ route('users.update', $user) }}';
                                            $dispatch('open-modal', 'edit-user-modal');
                                        ">
                                            {{ __('Edit') }}
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 px-6 text-center">{{ __('No users found.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $users->links('components.pagination.pagination') }}
                </div>
            </div>

            <x-modal name="edit-user-modal" focusable>
                <form method="POST" x-bind:action="action" class="p-6">
                    @csrf
                    @method('PUT')

                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Edit Role for:') }} <span class="font-bold" x-text="userToEdit.name"></span>
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        {{ __('Select the roles to be assigned to this user.') }}
                    </p>

                    <div class="mt-6">
                        <x-input-label for="roles" value="{{ __('Roles') }}" />
                        <div class="mt-2 grid grid-cols-2 gap-4">
                            <template x-for="role in allRoles" :key="role.id">
                                <label class="flex items-center">
                                    <input type="checkbox" name="roles[]" :value="role.name"
                                        :checked="userToEdit.roles && userToEdit.roles.map(r => r.id).includes(role.id)"
                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                    <span class="ms-2 text-sm text-gray-600" x-text="role.name"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <x-secondary-button x-on:click="$dispatch('close')">
                            {{ __('Cancel') }}
                        </x-secondary-button>
                        <x-primary-button class="ms-3">
                            {{ __('Save Changes') }}
                        </x-primary-button>
                    </div>
                </form>
            </x-modal>
        </div>
    </div>
</x-app-layout>
