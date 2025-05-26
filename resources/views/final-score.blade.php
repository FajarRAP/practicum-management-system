<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Final Score') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">
            @foreach ($courses as $course => $assessments)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <h3 class="text-xl font-medium text-gray-800 p-4">
                        {{ $course }}
                    </h3>

                    <div class="relative overflow-x-auto shadow-md sm:rounded-lg min-h-96 flex flex-col justify-between">
                        <table class="w-full text-sm text-left rtl:text-right text-gray-500 border-gray-300">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Name') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Student Number') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Active Score') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Report Score') }}
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        {{ __('Final Score') }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($assessments as $assessment)
                                    <tr>
                                        <td class="px-6 py-4">
                                            {{ $assessment['user']['name'] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $assessment['user']['identity_number'] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $assessment['active_score'] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $assessment['report_score'] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $assessment['final_score'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
