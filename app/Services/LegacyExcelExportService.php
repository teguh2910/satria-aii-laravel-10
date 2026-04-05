<?php

namespace App\Services;

use Excel;

class LegacyExcelExportService
{
    /**
     * @param array<string, array<int, array<string, mixed>>> $sheets
     */
    public function export(string $filename, array $sheets): void
    {
        Excel::create($filename, function ($excel) use ($sheets) {
            foreach ($sheets as $sheetName => $rows) {
                $excel->sheet($sheetName, function ($sheet) use ($rows) {
                    $sheet->fromArray($rows);
                });
            }
        })->export('xlsx');
    }
}
