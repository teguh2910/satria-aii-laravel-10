<?php

namespace App\Services;

use App\sj;
use App\sj_error;
use Carbon\Carbon;

class SjWorkflowService
{
    public function scanSjBalik(string $doaii, string $username): array
    {
        $doaii = trim($doaii);
        $record = sj::where('doaii', $doaii)->first();

        if (! $record) {
            sj_error::create([
                'doaii' => $doaii,
                'user_scan' => $username,
            ]);

            return [
                'type' => 'danger',
                'message' => 'SJ/DO Error !!! Nomor SJ/DO ' . $doaii,
            ];
        }

        if (! empty($record->sj_balik)) {
            return [
                'type' => 'danger',
                'message' => 'SJ Sudah BALIK !!! Nomor SJ/DO ' . $doaii,
            ];
        }

        $record->update([
            'sj_balik' => Carbon::now(),
            'user_ppic_scan' => $username,
        ]);

        return [
            'type' => 'message',
            'message' => 'Sukses Simpan Nomor DO/SJ = ' . $doaii,
        ];
    }

    public function scanTerimaFinance(string $doaii, string $username): array
    {
        $doaii = trim($doaii);
        $record = sj::where('doaii', $doaii)->first();

        if (! $record) {
            sj_error::create([
                'doaii' => $doaii,
                'user_scan' => $username,
            ]);

            return [
                'type' => 'danger',
                'message' => 'SJ ERROR, NOMOR SJ = ' . $doaii,
            ];
        }

        $record->update([
            'terima_finance' => Carbon::now(),
            'user_finance_scan' => $username,
        ]);

        return [
            'type' => 'message',
            'message' => 'Sukses Simpan Nomor Invoice = ' . ($record->invoice ?? '-'),
            'with' => ['success' => 'Berhasil'],
        ];
    }
}
