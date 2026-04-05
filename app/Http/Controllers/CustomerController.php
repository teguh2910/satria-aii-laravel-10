<?php

namespace App\Http\Controllers;

use App\customer;
use App\Http\Requests\CustomerRequest;
use App\Services\LegacyExcelImportService;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Session;

class CustomerController extends Controller
{
    public function __construct(private LegacyExcelImportService $excelImportService)
    {
        $this->middleware('auth');
    }

    public function customer_store(Request $request)
    {
        $insert = [];

        if (Input::hasFile('customer')) {
            $path = Input::file('customer')->getRealPath();
            $insert = $this->excelImportService->mapRows($path, function ($value) {
                return [
                    'customer_code' => $value->customer_code,
                    'customer_name' => $value->customer_name,
                ];
            });

            if (! empty($insert)) {
                foreach ($insert as $row) {
                    customer::create($row);
                }
                Session::flash('message', 'Sukses Upload Customer, Total=' . count($insert));
            } else {
                Session::flash('danger', 'Gagal Upload, File kosong atau format salah');
            }

            return redirect('/customer');
        }

        if ($request->has('customer_code') && $request->has('customer_name')) {
            $validated = $request->validate([
                'customer_code' => 'required|string',
                'customer_name' => 'required|string',
            ]);

            customer::create($validated);

            return response()->json(['success' => true, 'message' => 'Customer berhasil ditambahkan']);
        }

        Session::flash('danger', 'Something Wrong Contact Administrator');
        return redirect('/customer');
    }

    public function data_customer()
    {
        $data = customer::select('id', 'customer_code', 'customer_name', 'created_at');

        return Datatables::of($data)
            ->editColumn('created_at', function ($row) {
                return $row->created_at ? \Carbon\Carbon::parse($row->created_at)->format('d/m/Y H:i') : '';
            })
            ->addColumn('action', function ($row) {
                return '<a class="btn btn-warning btn-xs" href="edit_customer/' . $row->id . '">Edit</a>
                <a class="btn btn-danger btn-xs" href="delete_customer/' . $row->id . '">Delete</a>
                ';
            })
            ->rawColumns(['action'])
            ->make();
    }

    public function customer()
    {
        $data = customer::all();
        return view('customer', compact('data'));
    }

    public function customer_create()
    {
        return view('customer_create');
    }

    public function customer_store_form(CustomerRequest $request)
    {
        customer::create($request->validated());

        Session::flash('message', 'Customer berhasil ditambahkan');
        return redirect('/customer');
    }

    public function customer_edit($id)
    {
        $data = customer::find($id);
        if (! $data) {
            Session::flash('danger', 'Customer tidak ditemukan');
            return redirect('/customer');
        }

        return view('customer_edit', compact('data'));
    }

    public function customer_update(CustomerRequest $request, $id)
    {
        $customer = customer::find($id);
        if (! $customer) {
            Session::flash('danger', 'Customer tidak ditemukan');
            return redirect('/customer');
        }

        $customer->update($request->validated());

        Session::flash('message', 'Customer berhasil diupdate');
        return redirect('/customer');
    }

    public function customer_delete($id)
    {
        $customer = customer::find($id);
        if (! $customer) {
            Session::flash('danger', 'Customer tidak ditemukan');
            return redirect('/customer');
        }

        $customer->delete();
        Session::flash('message', 'Customer berhasil dihapus');
        return redirect('/customer');
    }
}
