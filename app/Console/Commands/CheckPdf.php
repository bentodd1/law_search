<?php

namespace App\Console\Commands;

use App\Models\CaseFile;
use DOMDocument;
use Exception;
use Goutte\Client;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Smalot\PdfParser\Parser;
use GuzzleHttp\Client as GuzzleClient;



class CheckPdf extends Command
{
    protected $signature = 'check:pdf';
    protected $description = 'Check a PDF file for specific text.';

    // TODO look at only GRNT
    public function handle()
    {
        $caseFiles = CaseFile::whereNull('contains_2d')
            ->whereHas('eventStatements', function ($query) {
                $query->where('code', 'GNEA');
            })
            ->whereHas('eventStatements', function ($query) {
                $query->where('code', 'GNRT');
            })
            ->get();

            foreach ($caseFiles as $caseFile) {
                $url = "https://tsdr.uspto.gov/docsview/sn{$caseFile->serial_number}";

                try {
                    $bool = $this->scanPdf($url);
                    $caseFile->contains_2d = $bool;
                    $caseFile->save();
                }
                catch(Exception $e) {
                    $caseFileId = $caseFile->id;
                    $this->info("Error processing caseFile $caseFileId");
                    $this->info($e->getMessage());
                    break;
                }

            }
        }


    public function scanPdf(string $url) {
        //$url = $this->argument('url');
        $url = $this->findLinkFromRequest($url);
        $url = $this->extractPdfUrl($url);
        if (substr($url, -4) === '.pdf') {
            $searchText = "2(d) Likelihood of Confusion Refusal";
            $pdfContent = file_get_contents($url);
            if ($pdfContent === false) {
                throw new Exception('No contents');
            }

            $parser = new Parser();
            $pdf = $parser->parseContent($pdfContent);
            $pages  = $pdf->getText();

            if (strpos($pages, $searchText) !== false) {
                return true;
            } else {
                return false;
            }
        } else {
            // Handle HTML content
            $htmlContent = $this->fetchHtmlContent($url); // Fetch using Guzzle or cURL
           return $this->processHtml($htmlContent);
        }


    }

    private function processHtml($htmlContent): bool {
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($htmlContent);
        libxml_clear_errors();

        $paragraphs = $dom->getElementsByTagName('p');
        foreach ($paragraphs as $paragraph) {
            if (strpos($paragraph->nodeValue, '2(d)') !== false) {
                return true; // Found '2(d)' in the HTML content
            }
        }
        return false; // '2(d)' not found in the HTML content
    }


    function extractPdfUrl($pageUrl) {
        $client = new Client();
        $crawler = $client->request('GET', $pageUrl);

        $scriptTagContent = $crawler->filter('script')->reduce(function ($node) {
            return strpos($node->text(), 'DocsList') !== false;
        })->text();
        $jsonString = str_replace('var DocsList =', '', $scriptTagContent);
        $this->info("Doc list json string");
        $this->info($jsonString);
        $urls= $this->extractUrlsFromJson($jsonString, "Non-Final Action");
        if(empty($urls)) {
            $urls = $this->extractUrlsFromJson($jsonString, "Offc Action Outgoing");
        }
            $this->info("\n Url is $urls");
        return $urls;
    }

    function extractUrlsFromJson($jsonString, $descriptionFilter = null) {
        $urls = [];

        // Decode the JSON string into an associative array
        $decoded = json_decode($jsonString, true);

        // Check if the decoding was successful
        if ($decoded && isset($decoded['caseDocs'])) {
            foreach ($decoded['caseDocs'] as $doc) {
                // Check if we are filtering by description
                if ($descriptionFilter !== null && $doc['description'] !== $descriptionFilter) {
                    continue;
                }

                // Check if 'urlPathList' is set and is an array
                if (isset($doc['urlPathList']) && is_array($doc['urlPathList'])) {
                    // Add URLs to the result array
                    foreach ($doc['urlPathList'] as $url) {
                        return $url;
                    }
                }
            }
        } else {
            throw new Exception("Error decoding JSON.");
        }

        return $urls;
    }

    private function fetchHtmlContent($url):string {
        $client = new GuzzleClient();
        $response = $client->request('GET', $url);
        return (string) $response->getBody();
    }


    public function findLinkFromRequest(string $url ) {
        $response = Http::withHeaders([
            'Accept' => 'application/json',
        ])->get($url);
        $this->info($response);
        if ($response->successful()) {
            // Get the response body
            $data = $response->body();
            $xml = simplexml_load_string($data);

          //  $namespaces = $xml->getNamespaces(true);

            foreach ($xml->xpath('//a[contains(@href, "NFIN")]') as $link) {
                $href = (string) $link['href']; // Get the href attribute of the link
                $fullUrl = "https://tsdr.uspto.gov" . $href; // Construct the full URL
                return $fullUrl;
            }
            foreach ($xml->xpath('//a[contains(@href, "OOA")]') as $link) {
                $href = (string) $link['href']; // Get the href attribute of the link
                $fullUrl = "https://tsdr.uspto.gov" . $href; // Construct the full URL
                return $fullUrl;
            }
        }

    }

}

