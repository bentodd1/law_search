<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&display=swap" rel="stylesheet">
    <style>
        .cursive-font {
            font-family: 'Great Vibes', cursive;
        }
    </style>
</head>
<body class="bg-gray-100">
<div class="container mx-auto px-4 py-6 text-center">
    <!-- Site Title -->
   <!-- <h1 class="text-4xl font-bold text-gray-800 mb-4">Monogram Legal</h1> -->
    <h1 class="text-4xl cursive-font text-gray-800 mb-4">Monogram Legal</h1>
    <img src="{{ asset('images/monogram-logo.webp') }}" alt="Monogram Logo" class="h-16 w-auto mx-auto">
    <form action="{{ url('/search') }}" method="GET" class="bg-white p-6 rounded shadow mb-8">
        <div class="mb-4">
            <input type="text" id="query" name="query" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Search</button>
    </form>
    @if(isset($cancelledCount) && isset($registeredCount))
        <h2 class="text-2xl font-bold mb-4">Trademark Search Results</h2>
    @endif

    @if(isset($cancelledCount) && isset($registeredCount))
        <div class="bg-white p-6 rounded shadow mb-8">
            <p class="text-md mb-2">Cancelled Trademarks: <span class="font-semibold">{{ $cancelledCount }}</span></p>
            <p class="text-md mb-2">Registered Trademarks: <span class="font-semibold">{{ $registeredCount }}</span></p>
            <p class="text-md">Percentage Registered: <span class="font-semibold">{{ number_format($percentage, 2) }}%</span></p>
        </div>
    @endif

    @if(isset($registeredTrademarks) && isset($cancelledTrademarks))
        <div class="bg-white p-6 rounded shadow mb-8">
            <h3 class="text-xl font-semibold mb-4">Registered Trademarks</h3>
            <table class="min-w-full table-auto">
                <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-center">Details</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                @foreach($registeredTrademarks as $trademark)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $trademark['source']['wordmark'] }}</td>
                        <td class="py-3 px-6 text-center"><a href="https://tsdr.uspto.gov/#caseNumber={{ $trademark['source']['id'] }}&caseSearchType=US_APPLICATION&caseType=DEFAULT&searchType=statusSearch" class="text-blue-500 hover:text-blue-600" target="_blank">View Trademark</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="bg-white p-6 rounded shadow">
            <h3 class="text-xl font-semibold mb-4">Cancelled Trademarks</h3>
            <table class="min-w-full table-auto">
                <thead>
                <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                    <th class="py-3 px-6 text-left">Name</th>
                    <th class="py-3 px-6 text-center">Details</th>
                </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                @foreach($cancelledTrademarks as $trademark)
                    <tr class="border-b border-gray-200 hover:bg-gray-100">
                        <td class="py-3 px-6 text-left whitespace-nowrap">{{ $trademark['source']['wordmark'] }}</td>
                        <td class="py-3 px-6 text-center"><a href="https://tsdr.uspto.gov/#caseNumber={{ $trademark['source']['id'] }}&caseSearchType=US_APPLICATION&caseType=DEFAULT&searchType=statusSearch" class="text-blue-500 hover:text-blue-600" target="_blank">View Trademark</a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <div class="my-4">
                @if(isset($cancelledPage) && $cancelledPage > 1)
                    <a href="{{ url('/search/cancelled/' . ($cancelledPage - 1)) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-l focus:outline-none focus:shadow-outline">Previous</a>
                @endif

                @if(isset($cancelledPage) && $cancelledHasMorePages)
                    <a href="{{ url('/search/cancelled/' . ($cancelledPage + 1)) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-r focus:outline-none focus:shadow-outline">Next</a>
                @endif
            </div>
        </div>
    @endif
</div>
</body>
</html>
