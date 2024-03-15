<x-app-layout>
    <!--<!DOCTYPE html>
    <html lang="en"> -->
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
    <div class="container mx-auto p-4">
        <h1 class="text-xl font-semibold mb-4">Employee Search</h1>
        <form action="{{ route('employee-search') }}" method="GET" class="mb-4">
            <input type="text" name="query" placeholder="Search employees..."
                   class="p-2 border border-gray-300 rounded-md">
            <button type="submit" class="p-2 bg-blue-500 text-white rounded-md">Search</button>
        </form>

        @if(isset($searchResults))
            <div>
                <h2 class="text-lg font-semibold mb-2">Results</h2>
                <ul>
                    @forelse ($searchResults as $employee)
                        <li class="p-2 border-b border-gray-200">
                            <a href="{{ route('employees.show', $employee->id) }}" class="text-blue-600 hover:text-blue-800">{{ $employee->name }}</a> - {{ $employee->position }}
                        </li>
                    @empty
                        <li class="p-2">No results found</li>
                    @endforelse
                </ul>
            </div>
        @endif
    </div>
    </body>
</x-app-layout>
