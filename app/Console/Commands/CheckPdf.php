<?php

namespace App\Console\Commands;

use Exception;
use Goutte\Client;
use Illuminate\Console\Command;
use Smalot\PdfParser\Parser;

class CheckPdf extends Command
{
    protected $signature = 'check:pdf {url}';
    protected $description = 'Check a PDF file for specific text.';

    public function handle()
    {
        $url = $this->argument('url');
        $url = $this->extractPdfUrl($url);
        $searchText = "2(d) Likelihood of Confusion Refusal";

        try {
            $pdfContent = file_get_contents($url);
            if ($pdfContent === false) {
                $this->error("Failed to download PDF.");
                return;
            }

            $parser = new Parser();
            $pdf = $parser->parseContent($pdfContent);
            $pages  = $pdf->getText();

            if (strpos($pages, $searchText) !== false) {
                $this->info("Text found in the document.");
            } else {
                $this->info("Text not found in the document.");
            }
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
        }
    }


    function extractPdfUrl($pageUrl) {
        $client = new Client();
        $crawler = $client->request('GET', $pageUrl);

        $scriptTagContent = $crawler->filter('script')->reduce(function ($node) {
            return strpos($node->text(), 'DocsList') !== false;
        })->text();
        $jsonString = str_replace('var DocsList =', '', $scriptTagContent);

        return $this->extractUrlsFromJson($jsonString, "Non-Final Action");

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

}

