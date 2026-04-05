<?php

namespace App\Http\Controllers;

use App\Http\Requests\DateRangeFilterRequest;
use App\sj;
use App\sj_error;
use Carbon\Carbon;
use Datatables;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect('/dashboard');
    }

    public function dashboard()
    {
        return view('dashboard');
    }

    public function sj_dashboard()
    {
        return view('sj_dashboard');
    }

    public function sj_outstanding()
    {
        return view('sj_outstanding');
    }

    public function sj_outstanding_finance()
    {
        return view('sj_outstanding_finance');
    }

    public function sj_error()
    {
        return view('sj_error');
    }

    public function data_sj()
    {
        $sjBalikKeyword = request()->input('sj_balik_filter', request()->input('columns.7.search.value', ''));
        $financeKeyword = request()->input('terima_finance_filter', request()->input('columns.8.search.value', ''));

        $data = $this->baseSjDashboardQuery();

        $this->applyBlankFilter($data, 'sjs.sj_balik', $sjBalikKeyword);
        $this->applyBlankFilter($data, 'sjs.terima_finance', $financeKeyword);

        return Datatables::of($data)
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? Carbon::parse($row->created_at)->format('Y-m-d H:i') : '';
            })
            ->editColumn('tanggal_delivery', function ($row) {
                return $row->tanggal_delivery ? Carbon::parse($row->tanggal_delivery)->format('Y-m-d') : '';
            })
            ->editColumn('sj_balik', function ($row) {
                if (! empty($row->sj_balik)) {
                    return $row->sj_balik;
                }

                return $row->updated_at ? Carbon::parse($row->updated_at)->format('Y-m-d H:i') : '';
            })
            ->editColumn('terima_finance', function ($row) {
                return $row->terima_finance;
            })
            ->editColumn('updated_at', function ($row) {
                return $row->updated_at ? Carbon::parse($row->updated_at)->format('Y-m-d H:i') : '';
            })
            ->make();
    }

    public function data_outstanding_sj()
    {
        $startDate = Carbon::now()->addDays(-7);
        $data = sj::select('sjs.created_at', 'sjs.tanggal_delivery', 'sjs.customer_code', 'customers.customer_name', 'sjs.pdsnumber', 'sjs.doaii', 'sjs.sj_balik', 'sjs.terima_finance')
            ->join('customers', 'customers.customer_code', 'sjs.customer_code')
            ->where('sjs.tanggal_delivery', '>=', $startDate)
            ->whereNull('sjs.terima_finance')
            ->groupBy('sjs.doaii');

        return Datatables::of($data)->make();
    }

    public function data_outstanding_sj_7_day()
    {
        $startDate = Carbon::now()->addDays(-7);
        $data = sj::select('sjs.created_at', 'sjs.tanggal_delivery', 'sjs.customer_code', 'customers.customer_name', 'sjs.pdsnumber', 'sjs.doaii', 'sjs.sj_balik', 'sjs.terima_finance')
            ->join('customers', 'customers.customer_code', 'sjs.customer_code')
            ->where('sjs.tanggal_delivery', '<=', $startDate)
            ->groupBy('sjs.doaii')
            ->whereNull('sjs.sj_balik');

        return Datatables::of($data)->make();
    }

    public function data_outstanding_sj_7_day_finance()
    {
        $startDate = Carbon::now()->addDays(-7);
        $data = sj::select('sjs.created_at', 'sjs.tanggal_delivery', 'sjs.customer_code', 'customers.customer_name', 'sjs.pdsnumber', 'sjs.doaii', 'sjs.sj_balik', 'sjs.terima_finance')
            ->join('customers', 'customers.customer_code', 'sjs.customer_code')
            ->where('sjs.tanggal_delivery', '<=', $startDate)
            ->groupBy('sjs.doaii')
            ->whereNull('sjs.terima_finance')
            ->whereNotNull('sjs.sj_balik');

        return Datatables::of($data)->make();
    }

    public function data_sj_error()
    {
        $data = sj_error::select('id', 'doaii', 'user_scan', 'created_at');

        return Datatables::of($data)
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? Carbon::parse($row->created_at)->format('d/m/Y H:i:s') : '';
            })
            ->make();
    }

    public function filter_view(DateRangeFilterRequest $request)
    {
        $validated = $request->validated();

        $fromDate = Carbon::createFromFormat('Y-m-d', $validated['from'])->startOfDay();
        $toDate = Carbon::createFromFormat('Y-m-d', $validated['to'])->endOfDay();

        $data = $this->baseSjDashboardQuery()
            ->whereBetween('sjs.tanggal_delivery', [$fromDate, $toDate])
            ->get();

        return view('dashboard_filter', compact('data'));
    }

    private function baseSjDashboardQuery()
    {
        $query = sj::select(
            'sjs.id',
            'sjs.created_at',
            'sjs.tanggal_delivery',
            'sjs.customer_code',
            'customers.customer_name',
            'sjs.pdsnumber',
            'sjs.doaii',
            'sjs.sj_balik',
            'sjs.terima_finance',
            'sj_errors.updated_at'
        )
            ->leftJoin('customers', 'customers.customer_code', 'sjs.customer_code')
            ->leftJoin('sj_errors', 'sj_errors.doaii', 'sjs.doaii')
            ->groupBy('sjs.doaii');

        return $query;
    }

    private function applyBlankFilter($query, string $column, ?string $keyword): void
    {
        $value = trim((string) $keyword);

        if ($value === '__BLANK__') {
            $query->where(function ($q) use ($column) {
                $q->whereNull($column)
                    ->orWhere($column, '')
                    ->orWhereRaw("TRIM($column) = ''");
            });
        }

        if ($value === '__NOT_BLANK__') {
            $query->whereNotNull($column)
                ->whereRaw("TRIM($column) <> ''");
        }
    }

}
