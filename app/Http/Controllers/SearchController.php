<?php

namespace App\Http\Controllers;

use App\Services\QueryGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SearchController extends Controller
{
    //TODO Add vue js
    public function search(Request $request)
    {

        // Default page number is 1 if not provided in the request
        $cancelledPage = $request->input('cancelledPage', 1);
        $registeredPage = $request->input('registeredPage', 1);

        // Assuming you have a fixed page size, for example, 10
        $pageSize = 10;
        $query = $request->input('query');
        $queryServiceGenerator = new QueryGeneratorService();
        $registeredResponse = Http::withHeaders($queryServiceGenerator->getHeaders())->post('https://tmsearch.uspto.gov/api/tmsearch', $queryServiceGenerator->simpleRegisteredQuery($query, $registeredPage));
        $cancelledResponse = Http::withHeaders($queryServiceGenerator->getHeaders())->post('https://tmsearch.uspto.gov/api/tmsearch', $queryServiceGenerator->simpleCanceledQuery($query, $cancelledPage));
        $registeredArray = $registeredResponse->json();
        $cancelledArray = $cancelledResponse->json();
        $registeredTrademarks = $registeredArray['hits']['hits'];

        foreach ($registeredTrademarks as &$registeredTrademark) {
            $url = $queryServiceGenerator->createUrl($registeredTrademark['source']['id'] );
            $htmlResponse = Http::withHeaders($queryServiceGenerator->getHeaders())->get($url)->body();
            $disclaimer = $queryServiceGenerator->findDisclaimerFromUrl($htmlResponse);
            $registeredTrademark['disclaimer'] = $disclaimer;
        }

        $cancelledTrademarks = $cancelledArray['hits']['hits'];

// Access the totalValue field
        $registeredCount = $registeredArray['hits']['totalValue'];
        $cancelledCount = $cancelledResponse->json()['hits']['totalValue'];
        $percentage = ($registeredCount / ($registeredCount + $cancelledCount)) * 100;
        $registeredHasMorePages = $this->hasMorePages($registeredTrademarks, $pageSize);
        $cancelledHasMorePages = $this->hasMorePages($cancelledTrademarks, $pageSize);

        // Convert the response to JSON
        //$jsonResponse = json_encode($response->json(), JSON_PRETTY_PRINT);

        // Print the JSON in a <pre> tag for readability
        return view('search', compact('registeredTrademarks', 'cancelledTrademarks', 'cancelledCount', 'registeredCount', 'percentage', 'cancelledPage', 'cancelledHasMorePages', 'registeredPage', 'registeredHasMorePages', 'query'));

    }

    private function hasMorePages($results, $pageSize): bool
    {
        // Assuming $results contains total count of the results
        return count($results) == $pageSize;
    }



}
