<?php

namespace App\Services;

use Excel;

class LegacyExcelImportService
{
    /**
     * @return array<int, mixed>
     */
    public function rowsFromPath(string $path): array
    {
        $data = Excel::load($path)->get();

        if (empty($data) || ! $data->count()) {
            return [];
        }

        return $data->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function mapRows(string $path, callable $mapper): array
    {
        $mapped = [];

        foreach ($this->rowsFromPath($path) as $row) {
            $item = $mapper($row);
            if (is_array($item)) {
                $mapped[] = $item;
            }
        }

        return $mapped;
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    public function uniqueRows(array $rows): array
    {
        return array_values(array_map('unserialize', array_unique(array_map('serialize', $rows))));
    }

    /**
     * @param array<int, array<string, mixed>> $rows
     * @return array<int, array<string, mixed>>
     */
    public function filterRows(array $rows, callable $predicate): array
    {
        return array_values(array_filter($rows, $predicate));
    }
}
