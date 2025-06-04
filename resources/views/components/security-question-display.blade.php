@props(['question'])

<div
    {{ $attributes->merge(['class' => 'w-full border border-gray-300 bg-white text-gray-700 p-3 rounded-md shadow-sm']) }}>
    {{ $question }}
</div>
