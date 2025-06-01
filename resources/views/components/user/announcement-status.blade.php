@props(['announcement'])

<td class="px-6 py-4">
    {{ __(
        $announcement->is_approved === null
            ? 'Waiting to Approve'
            : ($announcement->is_approved === 0
                ? 'Not Approved'
                : 'Approved'),
    ) }}
</td>
