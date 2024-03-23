<x-app-layout>
    <!-- Your existing head section might already be included in x-app-layout -->

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">{{ $employee->name }}</h1>
            <p class="mb-3">Number of Cases: <span class="font-semibold">{{ $caseCount }}</span></p>

            <h2 class="text-xl font-semibold mb-2">Associated Cases</h2>
            <ul>
                @foreach ($cases as $case)
                    <li class="p-2 border-b border-gray-200">
                        <!-- Replace 'cases.show' with the actual route name and $case->id with the case's identifier -->
                        <a href="{{ route('cases.show', $case->id) }}" class="text-blue-600 hover:text-blue-800">
                            {{ $case->mark_identification }} - {{ $case->filing_date }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-app-layout>

