<?php

namespace App\Services;

class QueryGeneratorService
{
    public function getHeaders(): array {
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
            'user-agent' => 'Mozilla/5.0 (compatible; Monogram/1.0; https://monogramlegal.com/)'
       //   'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
        ];
    }

    public function simpleCanceledQuery(string $term): array
    {
        return [
            'query' => [
                'bool' => [
                    'must' => [
                        'match_phrase' => ['WM' => $term]
                    ],
                    'filter' => [
                        'term' => ['LD' => 'false'] // Assuming 'LD' indicates whether a trademark is live or not
                    ]
                ]
            ],
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

    public function simpleRegisteredQuery(string $term): array {
        return [
            'query' => [
                'bool' => [
                    'must' => [
                        'match_phrase' => ['WM' => $term]
                    ],
                    'filter' => [
                        'term' => ['LD' => 'true'] // Assuming 'LD' is 'true' for cancelled trademarks
                    ]
                ]
            ],
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

}
