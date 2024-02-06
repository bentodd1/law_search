<?php

namespace App\Http\Controllers;

use App\Services\QueryGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        //TODO goodsAndServices IC 013: Fireworks."

        $searchTerm = $request->input('query');

        $queryServiceGenerator = new QueryGeneratorService();
        $registeredResponse = Http::withHeaders($queryServiceGenerator->getHeaders())->post('https://tmsearch.uspto.gov/api/tmsearch', $queryServiceGenerator->simpleRegisteredQuery($searchTerm));
        $cancelledResponse = Http::withHeaders($queryServiceGenerator->getHeaders())->post('https://tmsearch.uspto.gov/api/tmsearch', $queryServiceGenerator->simpleCanceledQuery($searchTerm));
        $registeredArray = $registeredResponse->json();
        $registeredTrademarks = $registeredArray['hits']['hits'];
        $cancelledTrademarks = $cancelledResponse->json()['hits']['hits'];

// Access the totalValue field
        $registeredCount = $registeredArray['hits']['totalValue'];
        $cancelledCount = $cancelledResponse->json()['hits']['totalValue'];
        $percentage = ($registeredCount/($registeredCount + $cancelledCount)) * 100;

        // Convert the response to JSON
        //$jsonResponse = json_encode($response->json(), JSON_PRETTY_PRINT);

        // Print the JSON in a <pre> tag for readability
        return view('search', compact('registeredTrademarks', 'cancelledTrademarks', 'cancelledCount', 'registeredCount', 'percentage'));

    }
}
