<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Roles & Permissions Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-4">
                    <div class="md:col-span-1 p-6 border-r">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Roles') }}</h3>
                        <ul>
                            @foreach ($roles as $role)
                                <li>
                                    <a href="{{ route('role-permission.index', ['role' => $role->id]) }}"
                                        class="block px-4 py-2 text-sm rounded-md {{ $activeRole->id === $role->id ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600 hover:bg-gray-50' }}">
                                        <span>{{ $role->name }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="md:col-span-3 p-6">
                        <form method="POST" action="{{ route('role.permissions.update', $activeRole) }}">
                            @csrf
                            @if ($activeRole)
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">
                                        {{ __('Permissions for Role') }}:
                                        <span class="text-indigo-600">{{ $activeRole->name }}</span>
                                    </h3>
                                    <x-primary-button type="submit">
                                        {{ __('Save Changes') }}
                                    </x-primary-button>
                                </div>

                                @error('permissions')
                                    <x-danger-alert :value="$message" />
                                @enderror

                                @foreach ($permissions as $groupName => $groupPermissions)
                                    <fieldset class="border p-4 rounded-lg mb-6">
                                        <legend class="px-2 font-semibold text-gray-700">
                                            {{ Str::of($groupName)->replace('_', ' ')->title() }}
                                            {{ __('Abilities') }}
                                        </legend>
                                        <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                            @foreach ($groupPermissions as $permission)
                                                <label class="flex items-center">
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission->name }}"
                                                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                                        {{ $rolePermissions->contains($permission->name) ? 'checked' : '' }}>
                                                    <span
                                                        class="ms-2 text-sm text-gray-600">{{ $permission->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    </fieldset>
                                @endforeach
                            @else
                                <p class="text-gray-500">
                                    {{ __('Please create a role to begin managing permissions.') }}</p>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
