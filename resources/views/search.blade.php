<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <!-- Add any additional CSS or JS here -->
</head>
<body>
<div class="container">
    <h1>Search</h1>
    <form action="{{ url('/search') }}" method="GET">
        <div class="form-group">
            <label for="query">Enter your search term:</label>
            <input type="text" id="query" name="query" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Search</button>
    </form>
</div>
<h1>Trademark Search Results</h1>

@if(isset($cancelledCount) && isset($registeredCount))
<div>
    <p>Cancelled Trademarks: {{ $cancelledCount }}</p>
    <p>Registered Trademarks: {{ $registeredCount }}</p>
    <p>Percentage Registered: {{ number_format($percentage, 2) }}%</p>
</div>
@endif
<!-- Check if there are search results -->
@if(isset($registeredTrademarks) && isset($cancelledTrademarks))

    <h2>Registered Trademarks</h2>
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>
        @foreach($registeredTrademarks as $trademark)
            <tr>
                <td>{{ $trademark['source']['wordmark'] }}</td>
                <td><a href="https://tsdr.uspto.gov/#caseNumber={{ $trademark['source']['id'] }}&caseSearchType=US_APPLICATION&caseType=DEFAULT&searchType=statusSearch" target="_blank">View Trademark</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h2>Cancelled Trademarks</h2>
    <table>
        <thead>
        <tr>
            <th>Name</th>
            <th>Details</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cancelledTrademarks as $trademark)
            <tr>
                <td>{{ $trademark['source']['wordmark'] }}</td>
                <td><a href="https://tsdr.uspto.gov/#caseNumber={{ $trademark['source']['id'] }}&caseSearchType=US_APPLICATION&caseType=DEFAULT&searchType=statusSearch" target="_blank">View Trademark</a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endif
</body>
</html>
