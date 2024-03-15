<x-app-layout>
    <!-- Your existing head section might already be included in x-app-layout -->

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">{{ $employee->name }}</h1>
            <p class="mb-3">Number of Cases: <span class="font-semibold">{{ $caseCount }}</span></p>

            <h2 class="text-xl font-semibold mb-2">Associated Cases</h2>
            <ul>
                @forelse ($cases as $case)
                    <li class="p-2 border-b border-gray-200">{{ $case->mark_identification }} - {{ $case->filing_date }}</li>
                @empty
                    <li class="p-2">No associated cases found.</li>
                @endforelse
            </ul>
        </div>
    </div>
</x-app-layout>

