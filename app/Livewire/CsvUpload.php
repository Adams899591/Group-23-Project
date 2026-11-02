<?php


// FILE: app/Livewire/CsvUpload.php
// The admin uploads a .csv file, we read it with PHP (no package) and show charts.
// WithFileUploads is built into Livewire.

namespace App\Livewire;

use App\Support\CsvAdmissionAnalyzer;
use App\Support\SampleStudentData;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Admission Trends')]
class CsvUpload extends Component
{
    use WithFileUploads;

    public $file;                    // the uploaded file (temporary)
    public ?array $report = null;    // the numbers for the charts (null = show the upload screen)
    public string $reportId = '';    // changes on every upload so the charts are rebuilt

    // Runs automatically as soon as the file finished uploading
    public function updatedFile(): void
    {
        $this->resetErrorBag();
        $this->report = null;

        // 1) Check the file (CSV only, max 5 MB)
        $this->validate(
            ['file' => ['required', 'file', 'max:5120']],
            ['file.max' => 'The file is too large. The maximum size is 5 MB.']
        );

        $extension = strtolower($this->file->getClientOriginalExtension());
        if (! in_array($extension, ['csv', 'txt'], true)) {
            $this->addError('file', 'Please upload a .csv file.');
            $this->file = null;

            return;
        }

        // 2) Read the file and build the report
        try {
            $this->report   = (new CsvAdmissionAnalyzer())->analyse($this->file->getRealPath(), $this->file->getClientOriginalName());
            $this->reportId = uniqid();
        } catch (\RuntimeException $e) {
            $this->addError('file', $e->getMessage());   // clear message for the admin
        } catch (\Throwable $e) {
            report($e);                                  // goes to storage/logs/laravel.log
            $this->addError('file', 'Sorry, this file could not be read. Please check that it is a valid CSV file.');
        }

        // 3) The uploaded copy is not needed any more
        $this->file->delete();
        $this->file = null;
    }

    // "Upload another file" button
    public function clearReport(): void
    {
        $this->report = null;
        $this->file   = null;
        $this->resetErrorBag();
    }

    // "Download sample CSV" button: builds the 300 records and streams them as a .csv file
    public function downloadSample(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');

            fputcsv($out, SampleStudentData::HEADERS, ',', '"', '\\');
            foreach (SampleStudentData::rows() as $row) {
                fputcsv($out, $row, ',', '"', '\\');
            }

            fclose($out);
        }, 'sample-student-records.csv', ['Content-Type' => 'text/csv']);
    }

    public function render()
    {
        return view('livewire.csv-upload')->layout('layouts.app');
    }
}