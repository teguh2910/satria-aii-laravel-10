<?php

namespace App\Support;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class LegacyExcel
{
    public static function load(string $path): LegacyExcelReader
    {
        return new LegacyExcelReader($path);
    }

    public static function create(string $filename, Closure $callback): LegacyExcelWriter
    {
        $writer = new LegacyExcelWriter($filename);
        $callback($writer);

        return $writer;
    }
}

class LegacyExcelReader
{
    public function __construct(private string $path)
    {
    }

    public function get(): Collection
    {
        $spreadsheet = IOFactory::load($this->path);
        $sheet = $spreadsheet->getActiveSheet();
        $raw = $sheet->toArray(null, true, true, false);

        if (empty($raw)) {
            return collect();
        }

        $headings = array_shift($raw);
        $mappedRows = [];

        foreach ($raw as $row) {
            $mapped = [];

            foreach ($headings as $index => $heading) {
                $key = $this->normalizeHeading($heading, $index);
                $mapped[$key] = $row[$index] ?? null;
            }

            $mappedRows[] = (object) $mapped;
        }

        return collect($mappedRows);
    }

    private function normalizeHeading(mixed $heading, int $index): string
    {
        $heading = trim((string) $heading);

        if ($heading === '') {
            return 'column_' . $index;
        }

        $key = Str::snake($heading);
        $key = preg_replace('/[^a-z0-9_]/', '', $key) ?: '';

        return $key !== '' ? $key : 'column_' . $index;
    }
}

class LegacyExcelWriter
{
    /**
     * @var array<int, array{name: string, rows: array<int, array<string, mixed>>}>
     */
    private array $sheets = [];

    public function __construct(private string $filename)
    {
    }

    public function sheet(string $name, Closure $callback): self
    {
        $sheet = new LegacyExcelSheet();
        $callback($sheet);

        $this->sheets[] = [
            'name' => $name,
            'rows' => $sheet->rows(),
        ];

        return $this;
    }

    public function export(string $type): void
    {
        $extension = strtolower($type) === 'csv' ? 'csv' : 'xlsx';
        $spreadsheet = new Spreadsheet();

        if (empty($this->sheets)) {
            $this->sheets[] = ['name' => 'Sheet1', 'rows' => []];
        }

        foreach ($this->sheets as $index => $sheetData) {
            $worksheet = $index === 0 ? $spreadsheet->getActiveSheet() : $spreadsheet->createSheet();
            $worksheet->setTitle(substr($sheetData['name'], 0, 31));
            $this->fillSheet($worksheet, $sheetData['rows']);
        }

        $tempFile = tempnam(sys_get_temp_dir(), 'legacy_excel_');
        $path = $tempFile . '.' . $extension;
        rename($tempFile, $path);

        $writer = IOFactory::createWriter($spreadsheet, $extension === 'csv' ? 'Csv' : 'Xlsx');
        $writer->save($path);

        $response = response()->download($path, $this->filename . '.' . $extension);
        $response->deleteFileAfterSend(true);
        $response->send();

        exit;
    }

    private function fillSheet($worksheet, array $rows): void
    {
        if (empty($rows)) {
            return;
        }

        $normalizedRows = [];
        $allKeys = [];

        foreach ($rows as $row) {
            $rowArray = (array) $row;
            $normalizedRows[] = $rowArray;
            foreach (array_keys($rowArray) as $key) {
                $allKeys[$key] = true;
            }
        }

        $headers = array_keys($allKeys);

        if (!empty($headers)) {
            $worksheet->fromArray($headers, null, 'A1');
        }

        $line = 2;
        foreach ($normalizedRows as $row) {
            $ordered = [];
            foreach ($headers as $key) {
                $ordered[] = $row[$key] ?? null;
            }
            $worksheet->fromArray($ordered, null, 'A' . $line);
            $line++;
        }
    }
}

class LegacyExcelSheet
{
    /**
     * @var array<int, array<string, mixed>>
     */
    private array $rows = [];

    public function fromArray(array $rows): self
    {
        $this->rows = [];

        foreach ($rows as $row) {
            $this->rows[] = (array) $row;
        }

        return $this;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function rows(): array
    {
        return $this->rows;
    }
}
