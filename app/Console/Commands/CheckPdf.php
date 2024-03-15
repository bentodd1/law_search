<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Smalot\PdfParser\Parser;

class CheckPdf extends Command
{
    protected $signature = 'check:pdf {url}';
    protected $description = 'Check a PDF file for specific text.';

    public function handle()
    {
        $url = $this->argument('url');
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
}

