<?php

namespace App\Console\Commands;

use App\Models\CaseFile;
use App\Models\CaseFileOwner;
use App\Models\Classification;
use App\Models\AmericanCode;
use App\Models\Correspondent;
use App\Models\Employee;
use App\Models\ScannedFile;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ProcessCaseFiles extends Command
{
    protected $signature = 'process:casefile';
    protected $description = 'Imports case files from an XML file';

    public function handle()
    {
        $storagePath = storage_path('app/xml_files');
        //  $directory = $this->argument('directory');
        $files = File::allFiles($storagePath);

        foreach ($files as $file) {
            $filename = $file->getRelativePathname();
            echo $filename;
            $scannedFile = ScannedFile::firstOrNew(['filename' => $filename]);

            if (!$scannedFile->scanned) {
                $filePath = storage_path("app/xml_files/$filename");
                if (!file_exists($filePath)) {
                    $this->error("XML file not found at {$filePath}");
                    return;
                }

                $xml = simplexml_load_file($filePath);
                foreach ($xml->{'application-information'}->{'file-segments'}->{'action-keys'}->{'case-file'} as $caseFileElement) {
                    $caseFile = CaseFile::create([
                        'serial_number' => (string)$caseFileElement->{'serial-number'},
                        'registration_number' => (string)$caseFileElement->{'registration-number'},
                        'transaction_date' =>  DateTime::createFromFormat('Ymd', (string)$caseFileElement->{'transaction-date'})->format('Y-m-d')

                    // ... other CaseFile fields ...
                    ]);
                    $employeeName = (string)$caseFileElement->{'case-file-header'}->{'employee-name'};
                    $markDrawingCode = trim((string)$caseFileElement->{'mark-drawing-code'});
                    $markDrawingCode = $markDrawingCode !== '' ? intval($markDrawingCode) : null;
                    $caseFileHeader =$caseFile->header()->create([
                        'filing_date' => $this->processDate((string)$caseFileElement->{'case-file-header'}->{'filing-date'}),
                        'status_code' => (string)$caseFileElement->{'case-file-header'}->{'status-code'},
                        'status_date' => $this->processDate((string)$caseFileElement->{'case-file-header'}->{'status-date'}),
                        'mark_identification' => (string)$caseFileElement->{'case-file-header'}->{'mark-identification'},
                        'mark_drawing_code' => $markDrawingCode,
                        'published_for_opposition_date' => $this->processDate((string)$caseFileElement->{'case-file-header'}->{'published-for-opposition-date'}),
                        'attorney_docket_number' => (string)$caseFileElement->{'case-file-header'}->{'attorney-docket-number'},
                        'attorney_name' => (string)$caseFileElement->{'case-file-header'}->{'attorney-name'},
                        // ... other CaseFileHeader fields ...
                    ]);
                    /** @var Employee|null $employee */
                    $employee = null;
                    if ($employeeName) {
                        $employee = Employee::firstOrCreate(['name' => $employeeName]);
                        $caseFileHeader->employee()->associate($employee);
                        $caseFileHeader->save();

                    }
                    $statements = $caseFileElement->{'case-file-statements'}->{'case-file-statement'};
                    if ($statements) {
                        foreach ($statements as $statementElement) {
                            $caseFile->statements()->create([
                                'type_code' => (string)$statementElement->{'type-code'},
                                'text' => (string)$statementElement->text,
                                // ... other fields ...
                            ]);
                        }
                    }
                    $classificationElements = $caseFileElement->classifications->classification;
                    if ($classificationElements) {
                        foreach ($classificationElements as $classificationElement) {
                            $classification = Classification::firstOrCreate([
                                'code' => (string)$classificationElement->{'international-code'},
                                // ... other fields for Classification ...
                            ]);

                            foreach ($classificationElement->{'us-code'} as $usCodeElement) {
                                $usCode = AmericanCode::firstOrCreate([
                                    'code' => (string)$usCodeElement,
                                    // ... other fields for USCode ...
                                ]);
                                $classification->americanCodes()->syncWithoutDetaching([$classification->id, $usCode->id]);
                            }

                            // Associating Classification with CaseFile
                            $caseFile->classifications()->syncWithoutDetaching([$classification->id]);
                        }
                    }
                    $caseFileOwnersElement = $caseFileElement->{'case-file-owners'};
                    if($caseFileOwnersElement) {
                        foreach ($caseFileOwnersElement->{'case-file-owner'} as $ownerElement) {
                            $caseFileOwnerData = [
                                'entry_number' => (string)$ownerElement->{"entry-number"},
                                'party_type' => (string)$ownerElement->{"party-type"},
                                'country' => (string)$ownerElement->nationality->country,
                                'legal_entity_type_code' => (string)$ownerElement->{"legal-entity-type-code"},
                                'entity_statement' => (string)$ownerElement->{"entity-statement"},
                                'party_name' => (string)$ownerElement->{"party-name"},
                                'address_1' => (string)$ownerElement->{"address-1"},
                                'city' => (string)$ownerElement->city,
                                'postcode' => (string)$ownerElement->postcode,

                            ];

                            $caseFileOwner = CaseFileOwner::updateOrCreate(
                                ['party_name' => $caseFileOwnerData['party_name']],
                                $caseFileOwnerData
                            );

                            // Sync without detaching to keep existing associations
                            $caseFile->caseFileOwners()->syncWithoutDetaching([$caseFileOwner->id]);
                        }
                    }


                    foreach ($caseFileElement->correspondent as $correspondentElement) {
                        $correspondentData = [
                            'name' => (string)$correspondentElement->{'address-1'},
                            'address1' => (string)$correspondentElement->{'address-2'},
                            'address2' => (string)$correspondentElement->{'address-3'},
                            'address3' => (string)$correspondentElement->{'address-4'},
                        ];

                        $correspondent = Correspondent::updateOrCreate(['name' => $correspondentData['name']], $correspondentData);

                        if($employee) {
                            if ($employee->name == $correspondent->name) {
                                $employee->correspondent()->associate($correspondent);
                                $employee->save();
                            }
                        }
                        $caseFile->correspondent()->associate($correspondent);
                        $caseFile->save();
                    }
                    $caseFileEventStatement = $caseFileElement->{'case-file-event-statements'}->{'case-file-event-statement'};
                    if($caseFileEventStatement) {
                        foreach ( $caseFileEventStatement as $eventStatementElement) {
                            $caseFile->eventStatements()->create([
                                'code' => (string)$eventStatementElement->code,
                                'type' => (string)$eventStatementElement->type,
                                'description_text' => (string)$eventStatementElement->descriptionText,
                                'date' => isset($eventStatementElement->date) ? (new DateTime($eventStatementElement->date))->format('Y-m-d') : null,
                                'number' => (int)$eventStatementElement->number,
                                // ... other fields ...
                            ]);
                        }
                    }
                }
                $scannedFile->scanned = true;
                $scannedFile->save();
            }
        }


        $this->info('Case files imported successfully.');
    }

    public function processDate(string|null $date): string|null
    {
        return ($date != '' && $date != '0000-00-00 00:00:00') ? $date : null;
    }
}
