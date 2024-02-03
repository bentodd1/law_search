<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Trademark Search Results</title>
    <!-- Add any additional CSS here -->
</head>
<body>
<h1>Trademark Search Results</h1>

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
            <td>{{ $trademark['name'] }}</td>
            <td><a href="https://tsdr.uspto.gov/#caseNumber={{ $trademark['id'] }}&caseSearchType=US_APPLICATION&caseType=DEFAULT&searchType=statusSearch" target="_blank">View Trademark</a></td>
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
            <td>{{ $trademark['name'] }}</td>
            <td><a href="https://tsdr.uspto.gov/#caseNumber={{ $trademark['id'] }}&caseSearchType=US_APPLICATION&caseType=DEFAULT&searchType=statusSearch" target="_blank">View Trademark</a></td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
