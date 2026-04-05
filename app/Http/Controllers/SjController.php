<?php

namespace App\Http\Controllers;

use App\customer;
use App\Http\Requests\ScanDoaiiRequest;
use App\Services\LegacyExcelExportService;
use App\Services\LegacyExcelImportService;
use App\Services\SjWorkflowService;
use App\sj;
use Auth;
use Carbon\Carbon;
use Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Session;

class SjController extends Controller
{
    public function __construct(
        private SjWorkflowService $sjWorkflowService,
        private LegacyExcelExportService $excelExportService,
        private LegacyExcelImportService $excelImportService
    ) {
        $this->middleware('auth');
    }

    public function upload_sj_dashboard()
    {
        return view('upload_sj_dashboard');
    }

    public function upload_sj_dashboard_store()
    {
        ini_set('memory_limit', '2048M');
        if (! Input::hasFile('sj')) {
            Session::flash('danger', 'Something Wrong Contact Administrator');
            return redirect('/sj/dashboard');
        }

        $path = Input::file('sj')->getRealPath();
        $insert = $this->excelImportService->mapRows($path, function ($value) {
            return [
                'tanggal_delivery' => $value->acgi_date,
                'customer_code' => $value->sold_to,
                'pdsnumber' => $value->external_delivery_id,
                'doaii' => $value->nomor_surat_jalan,
            ];
        });
        $insert = $this->excelImportService->uniqueRows($insert);
        $insert = $this->excelImportService->filterRows($insert, function ($value) {
            return ! is_null($value['doaii']) && $value['doaii'] !== '';
        });

        if (empty($insert)) {
            Session::flash('danger', 'Gagal Upload SJ');
            return redirect('/sj/dashboard');
        }

        foreach ($insert as $row) {
            if ($row['tanggal_delivery'] != null) {
                sj::create($row);
            }
        }

        $totalUpload = 'Sukses Scan SJ, Total Upload=' . count($insert) . ' SJ';
        Session::flash('message', $totalUpload);
        return redirect('/sj/dashboard');
    }

    public function update_sj_balik_ppic_upload()
    {
        if (! Input::hasFile('update_sj_balik_ppic')) {
            Session::flash('danger', 'Something Wrong Contact Administrator');
            return redirect('/sj/dashboard');
        }

        $result = $this->processDoaiiStatusUpload(
            Input::file('update_sj_balik_ppic')->getRealPath(),
            'sj_balik',
            'SJ Sudah Balik'
        );

        if ($result['successCount'] > 0) {
            Session::flash('message', 'Sukses Scan SJ, Total Upload=' . $result['successCount'] . ' SJ');
        } elseif ($result['alreadyCount'] > 0) {
            Session::flash('danger', 'Gagal Upload ' . $result['alreadyCount'] . ' SJ Sudah Balik');
        } else {
            Session::flash('danger', 'Gagal Upload SJ');
        }

        if (! empty($result['sheets'])) {
            $this->excelExportService->export('SJ Error', $result['sheets']);
        }

        return redirect('/sj/dashboard');
    }

    public function sj_balik()
    {
        return view('sj_balik');
    }

    public function sj_balik_store(ScanDoaiiRequest $request)
    {
        $result = $this->sjWorkflowService->scanSjBalik(
            (string) $request->input('doaii'),
            (string) optional(Auth::user())->name
        );

        Session::flash($result['type'], $result['message']);

        return redirect('/sj_balik');
    }

    public function terima_finance()
    {
        $data = sj::groupBy('doaii')->get();
        return view('terima_finance', compact('data'));
    }

    public function update_fin_upload()
    {
        if (! Input::hasFile('update_fin_upload')) {
            Session::flash('danger', 'Something Wrong Contact Administrator');
            return redirect('/sj/dashboard');
        }

        $result = $this->processDoaiiStatusUpload(
            Input::file('update_fin_upload')->getRealPath(),
            'terima_finance',
            'SJ Sudah Terima Finance'
        );

        if ($result['successCount'] > 0) {
            Session::flash('message', 'Sukses Scan SJ, Total Upload=' . $result['successCount'] . ' SJ');
        } elseif ($result['alreadyCount'] > 0) {
            Session::flash('danger', 'Gagal Upload ' . $result['alreadyCount'] . ' SJ Sudah Kirim Finance');
        } else {
            Session::flash('danger', 'Gagal Upload SJ');
        }

        if (! empty($result['sheets'])) {
            $this->excelExportService->export('SJ Error', $result['sheets']);
        }

        return redirect('/sj/dashboard');
    }

    public function terima_finance_store(ScanDoaiiRequest $request)
    {
        $result = $this->sjWorkflowService->scanTerimaFinance(
            (string) $request->input('doaii'),
            (string) optional(Auth::user())->name
        );

        Session::flash($result['type'], $result['message']);

        $redirect = redirect('/terima_finance');
        if (! empty($result['with']) && is_array($result['with'])) {
            return $redirect->with($result['with']);
        }

        return $redirect;
    }

    public function del_ppic($id)
    {
        sj::where('id', $id)->delete();
        Session::flash('warning', 'PDS NUMBER berhasil dihapus');
        return redirect('/dashboard');
    }

    public function sj_update($id)
    {
        $data = sj::where('id', $id)->first();
        return view('sj_update', compact('data'));
    }

    public function sj_update_store(Request $request, $id)
    {
        $validated = $request->validate([
            'tanggal_delivery' => ['required', 'date'],
            'customer_code' => ['required', 'string', 'max:255'],
            'pdsnumber' => ['required', 'string', 'max:255'],
            'doaii' => ['required', 'string', 'max:255'],
            'sj_balik' => ['nullable', 'date'],
            'terima_finance' => ['nullable', 'date'],
            'invoice' => ['nullable', 'string', 'max:255'],
            'user_ppic_scan' => ['nullable', 'string', 'max:255'],
            'user_finance_scan' => ['nullable', 'string', 'max:255'],
        ]);

        sj::where('id', $id)->update($validated);
        Session::flash('warning', 'EDIT data BERHASIL Bro');
        return redirect('dashboard');
    }

    public function create_sj()
    {
        $data = customer::all();
        return view('create_sj', compact('data'));
    }

    public function create_sj_store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_delivery' => ['required', 'date'],
            'customer_code' => ['required', 'string', 'max:255'],
            'pdsnumber' => ['required', 'string', 'max:255'],
            'doaii' => ['required', 'string', 'max:255'],
            'sj_balik' => ['nullable', 'date'],
            'terima_finance' => ['nullable', 'date'],
            'invoice' => ['nullable', 'string', 'max:255'],
            'user_ppic_scan' => ['nullable', 'string', 'max:255'],
            'user_finance_scan' => ['nullable', 'string', 'max:255'],
        ]);

        sj::create($validated);
        return redirect('create/sj');
    }

    public function download_sj()
    {
        $sj = sj::all();
        Excel::create('sj', function ($excel) use ($sj) {
            $excel->sheet('Sheet 1', function ($sheet) use ($sj) {
                $sheet->fromArray($sj);
            });
        })->export('xlsx');
    }

    /**
     * @return array{successCount:int,alreadyCount:int,sheets:array<string,array<int,array<string,string>>>}
     */
    private function processDoaiiStatusUpload(string $path, string $statusColumn, string $alreadySheetName): array
    {
        $rows = $this->excelImportService->rowsFromPath($path);

        $missing = [];
        $already = [];
        $success = [];
        $successCount = 0;
        $alreadyCount = 0;

        if (! empty($rows)) {
            foreach ($rows as $row) {
                $doaii = trim((string) ($row->doaii ?? ''));

                if ($doaii === '') {
                    continue;
                }

                $record = sj::where('doaii', $doaii)->whereNotNull('doaii')->first();

                if (! $record) {
                    $missing[] = ['doaii' => $doaii];
                    continue;
                }

                if (! empty($record->{$statusColumn})) {
                    $already[] = ['doaii' => $record->doaii];
                    $alreadyCount++;
                    continue;
                }

                $record->update([$statusColumn => Carbon::now()]);
                $success[] = ['doaii' => $record->doaii];
                $successCount++;
            }
        }

        $sheets = [];

        if (! empty($missing)) {
            $sheets['SJ Tidak Ada Di Master'] = $missing;
        }

        if (! empty($already)) {
            $sheets[$alreadySheetName] = $already;
        }

        if (! empty($success)) {
            $sheets['SJ Sukses Upload'] = $success;
        }

        return [
            'successCount' => $successCount,
            'alreadyCount' => $alreadyCount,
            'sheets' => $sheets,
        ];
    }
}
