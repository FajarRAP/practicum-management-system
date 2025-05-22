@props(['disabled' => false])

<input type="file" @disabled($disabled)
    {{ $attributes->merge([
        'class' =>
            'block text-sm text-gray-700 border border-gray-300 rounded-md shadow-sm cursor-pointer focus:outline-none focus:border-indigo-500 focus:ring-indigo-500 file:mr-4 file:ms-1 file:my-1 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100',
    ]) }}>
