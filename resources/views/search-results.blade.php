<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results</title>
    <!-- Add your CSS here -->
</head>
<body>
    <h1>Search Results</h1>

    @if(isset($responseData['error']))
        <p>Error: {{ $responseData['error'] }}</p>
    @else
        <div>
            @foreach($responseData as $data)
                <div>
                    <!-- Display your data here -->
                    <p>{{ $data['someField'] }}</p> <!-- Example, adjust according to your actual data structure -->
                </div>
            @endforeach
        </div>
    @endif
</body>
</html>
