<?php

namespace App\Console\Commands;

use App\Models\CaseFile;
use App\Models\Classification;
use App\Models\USCode;
use Illuminate\Console\Command;

class ProcessCaseFiles extends Command
{
    protected $signature = 'process:casefile';
    protected $description = 'Imports case files from an XML file';

    public function handle()
    {
        $filePath = storage_path('app/xml_files/caseFileForRockyKeno.xml');
        if (!file_exists($filePath)) {
            $this->error("XML file not found at {$filePath}");
            return;
        }

        $xml = simplexml_load_file($filePath);
        foreach ($xml->{'case-file'} as $caseFileElement) {
            $caseFile = CaseFile::create([
                'serial_number' => (string)$caseFileElement->{'serial-number'},
                'registration_number' => (string)$caseFileElement->{'registration-number'},
                // ... other CaseFile fields ...
            ]);

            $caseFile->header()->create([
                'filing_date' => (string)$caseFileElement->{'case-file-header'}->{'filing-date'},
                'status_code' => (string)$caseFileElement->{'case-file-header'}->{'status-code'},
                'status_date' => (string)$caseFileElement->{'case-file-header'}->{'status-date'},
                'mark_identification' => (string)$caseFileElement->{'case-file-header'}->{'mark-identification'},
                'mark_drawing_code' => (string)$caseFileElement->{'case-file-header'}->{'mark-drawing-code'},
                'published_for_opposition_date' => (string)$caseFileElement->{'case-file-header'}->{'published-for-opposition-date'},
                'attorney_docket_number' => (string)$caseFileElement->{'case-file-header'}->{'attorney-docket-number'},
                'attorney_name' => (string)$caseFileElement->{'case-file-header'}->{'attorney-name'},
                // ... other CaseFileHeader fields ...
            ]);

            foreach ($caseFileElement->{'case-file-statements'}->{'case-file-statement'} as $statementElement) {
                $caseFile->statements()->create([
                    'type_code' => (string)$statementElement->{'type-code'},
                    'text' => (string)$statementElement->text,
                    // ... other fields ...
                ]);
            }

            foreach ($caseFileElement->classifications->classification as $classificationElement) {
                $classification = Classification::firstOrCreate([
                    'code' => (string)$classificationElement->{'international-code'},
                    // ... other fields for Classification ...
                ]);

                foreach ($classificationElement->{'us-code'} as $usCodeElement) {
                    $usCode = USCode::firstOrCreate([
                        'code' => (string)$usCodeElement,
                        'classification_id' => $classification->id
                        // ... other fields for USCode ...
                    ]);
                    $usCodeId = $usCode->id; // Make sure this is getting a valid 'id' value
                    $classification->usCodes()->syncWithoutDetaching([$usCodeId]);
                }

                // Associating Classification with CaseFile
                $caseFile->classifications()->syncWithoutDetaching([$classification->id]);
            }
        }


        $this->info('Case files imported successfully.');
    }
}
