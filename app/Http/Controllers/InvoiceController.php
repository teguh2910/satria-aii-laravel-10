<?php

namespace App\Http\Controllers;

use App\sj;
use App\Services\LegacyExcelImportService;
use Datatables;
use DB;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Session;

class InvoiceController extends Controller
{
    public function __construct(private LegacyExcelImportService $excelImportService)
    {
        $this->middleware('auth');
    }

    public function invoice()
    {
        return view('upload_invoice');
    }

    public function update_invoice_upload()
    {
        $insert = [];

        if (Input::hasFile('invoice')) {
            $path = Input::file('invoice')->getRealPath();
            $insert = $this->excelImportService->mapRows($path, function ($value) {
                return [
                    'doaii' => $value->nomor_surat_jalan,
                    'invoice' => $value->no_invoice,
                ];
            });

            $insert = $this->excelImportService->filterRows($insert, function ($value) {
                return ! is_null($value['invoice']) && $value['invoice'] !== '';
            });

            foreach ($insert as $row) {
                sj::where('doaii', $row['doaii'])->update(['invoice' => $row['invoice']]);
            }
        } else {
            Session::flash('danger', 'Something Wrong Contact Administrator');
        }

        return redirect('/dashboard');
    }

    public function data_invoice()
    {
        $data = sj::select('invoice', DB::raw('COUNT(terima_finance) as terima_finance_count'), DB::raw('COUNT(doaii) as do_aii_count'))
            ->groupBy('invoice')
            ->havingRaw('count(terima_finance)!=count(doaii)');

        return Datatables::of($data)->make();
    }

    public function invoicing()
    {
        return view('invoicing');
    }
}
