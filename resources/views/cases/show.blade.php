<!-- cases/show.blade.php -->
<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
            <h1 class="text-2xl font-bold mb-4">Case Details</h1>

            <p><strong>Serial Number:</strong> {{ $case->serial_number }}</p>
            <p><strong>Mark Identification:</strong> {{ $case->mark_identification }}</p>
            <p><strong>USPTO Link:</strong> <a href="https://tsdr.uspto.gov/#caseNumber={{ $case->serial_number }}&caseSearchType=US_APPLICATION&caseType=DEFAULT&searchType=statusSearch" target="_blank" class="text-blue-600 hover:text-blue-800">View on USPTO</a></p>
            <p><strong>Contains 2(d):</strong> {{ $case->contains_2d ? 'Yes' : 'No' }}</p>
            <!--<p><strong>Overcame Rejection:</strong> {{ $case->overcame_rejection ? 'Yes' : 'No' }}</p> -->
            <p><strong>Status:</strong> {{ $case->status }}</p>

            <!-- Back to search results or other navigation link -->
        </div>
    </div>
</x-app-layout>
