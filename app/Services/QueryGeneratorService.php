<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;

class QueryGeneratorService
{
    public function getHeaders(): array
    {
        return [
            'authority' => 'tmsearch.uspto.gov',
            'accept' => 'application/json, text/plain, */*',
            'accept-language' => 'en-US,en;q=0.9',
            'content-type' => 'application/json',
            'origin' => 'https://tmsearch.uspto.gov',
            'referer' => 'https://tmsearch.uspto.gov/search/search-results',
            'sec-ch-ua' => '"Not_A Brand";v="8", "Chromium";v="120", "Google Chrome";v="120"',
            'sec-ch-ua-mobile' => '?0',
            'sec-ch-ua-platform' => '"macOS"',
            'sec-fetch-dest' => 'empty',
            'sec-fetch-mode' => 'cors',
            'sec-fetch-site' => 'same-origin',
            //'cookie' => '_ga_M4L1KRPWXE=GS1.1.1706644460.2.0.1706644465.0.0.0; _ga_Q5HVZK168H=GS1.1.1706970544.10.0.1706970544.0.0.0; _gid=GA1.2.1158049138.1707369181; _ga_6K78XHX69P=GS1.1.1707369181.12.1.1707369550.0.0.0; _ga=GA1.1.174350875.1706059600; _ga_CD30TTEK1F=GS1.1.1707411784.13.0.1707411784.0.0.0; _ga_CSLL4ZEK4L=GS1.1.1707411784.22.0.1707411784.0.0.0; QSI_SI_80n7m7xDr1KaBBc_intercept=true; aws-waf-token=cd5c45c3-8852-44a2-98f0-5641f25f4e99:EgoAvM0bYwATAAAA:fRyOgDGjbxPAQgYafRCTatbJSX/eNLTyPa7yIGP03AQpKCAolR0ggUDTgCeDYth+lkyOV+a/BwtUNHu95rAZtel/fX3lrBVntIK0xdYCX7NITtOLaKIjkiwcqYbHJk1uoKUBw8znBJRWudCFxZ+0Tj8Hxq3pEdK5TVg1iBsvzycOUUly5NrqFe5Y5GDxuFi3KfBIYyvj7cJZvmOUEMDPuw2/BtzcoYnUBfEOZSw9lgI1OCHRWKbsXxRwV3K65cEjkrmb4AZKWhKQ',
            'user-agent' => 'Mozilla/5.0 (compatible; Monogram/1.0; https://monogramlegal.com/)',
           //    'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',

        ];
    }

    public function simpleCanceledQuery(string $term, int $from =0): array
    {
        return [
            'query' => [
                'bool' => [
                    'must' => [
                        $this->getMachPart($term)
                    ],
                    'filter' => [
                        'term' => ['LD' => 'false'] // Assuming 'LD' indicates whether a trademark is live or not
                    ]
                ]
            ],
            "size" => 10,
            "from" => 0,
            "track_total_hits" => true,
            '_source' => [
                "abandonDate",
                "alive",
                "cancelDate",
                "coordinatedClass",
                "currentBasis",
                "drawingCode",
                "filedDate",
                "goodsAndServices",
                "id",
                "internationalClass",
                "markDescription",
                "ownerFullText",
                "ownerName",
                "ownerType",
                "priorityDate",
                "registrationDate",
                "registrationId",
                "registrationType",
                "supplementalRegistrationDate",
                "translate",
                "usClass",
                "wordmark",
                "wordmarkPseudoText"]
        ];
    }

    public function simpleRegisteredQuery(string $term, int $from =0): array
    {
        return [
            'query' => [
                'bool' => [
                    'must' => [
                        $this->getMachPart($term)
                    ],
                    'filter' => [
                        'term' => ['LD' => 'true'] // Assuming 'LD' is 'true' for cancelled trademarks
                    ]
                ]
            ],
            "size" => 10,
            "from" =>0,
            "track_total_hits" => true,
            '_source' => [
                "abandonDate",
                "alive",
                "cancelDate",
                "coordinatedClass",
                "currentBasis",
                "drawingCode",
                "filedDate",
                "goodsAndServices",
                "id",
                "internationalClass",
                "markDescription",
                "ownerFullText",
                "ownerName",
                "ownerType",
                "priorityDate",
                "registrationDate",
                "registrationId",
                "registrationType",
                "supplementalRegistrationDate",
                "translate",
                "usClass",
                "wordmark",
                "wordmarkPseudoText"]
        ];
    }

    public function getMachPart($term): array
    {
        if (strpos($term, ' ') !== false) {
            // Use match_phrase for multi-word terms
            return [
                'match_phrase' => ['WM' => $term]
            ];
        } else {
            // Use match for single-word terms
            return [
                'wildcard' => ['WM' => '*' . $term . '*']
            ];

        }
    }

    public function findDisclaimerFromUrl($html)
    {


        // Check if the content was fetched successfully
        if ($html === false) {
            return "Error fetching HTML content";
        }

        // Create a new DOMDocument and load the HTML content
        $dom = new DOMDocument;
        @$dom->loadHTML($html);

        // Create a new DOMXPath object
        $xpath = new DOMXPath($dom);

        // XPath query to find the disclaimer value
        $query = "//div[contains(., 'Disclaimer')]/following-sibling::div";
        $entries = $xpath->query($query);

        // Check if the disclaimer is found and return its value
        if ($entries->length > 0) {
            return trim($entries->item(0)->nodeValue);
        } else {
            return "No disclaimer found";
        }
    }

    public function createUrl(string|int $trademarkId): string{
        return "https://tsdr.uspto.gov/statusview/sn" . $trademarkId;

    }

}
