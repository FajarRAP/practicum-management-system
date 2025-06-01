@props(['announcement'])

<td class="px-6 py-4 flex flex-col gap-2">
    <form action="{{ route('announcement.confirm', $announcement) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="is_approved" value="true">
        <x-primary-button class="w-full justify-center">
            {{ __('Approve') }}
        </x-primary-button>
    </form>
    <form action="{{ route('announcement.confirm', $announcement) }}" method="POST">
        @csrf
        @method('PATCH')
        <input type="hidden" name="is_approved" value="false">
        <x-danger-button class="w-full justify-center">
            {{ __('Decline') }}
        </x-danger-button>
    </form>
</td>
