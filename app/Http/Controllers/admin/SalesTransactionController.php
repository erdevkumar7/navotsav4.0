<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class SalesTransactionController extends Controller
{

    public function posSales(){

        return view('sales.pos');
    }


    public function posSalesData(Request $request)
    {
        $cashSales = Transaction::with(['cashPayments', 'user', 'event'])
            ->where('payment_method', 'cash')
            ->where('status', 'completed')
            ->orderByDesc('id');

        return DataTables::of($cashSales)
            ->addIndexColumn()
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d-m-Y H:i');
            })
            ->addColumn('user', function ($row) {
                return $row->user?->name ?? 'N/A';
            })
            ->addColumn('event', function ($row) {
                return $row->event?->title ?? 'N/A';
            })
            ->addColumn('amount', function ($row) {
                return number_format($row->amount, 2);
            })
            ->addColumn('breakdown', function ($row) {
                if ($row->cashPayments->isEmpty()) {
                    return '-';
                }

                $html = '';
                foreach ($row->cashPayments as $c) {
                    $html .= "₹{$c->denomination} x {$c->qty} = ₹{$c->total}<br>";
                }
                return $html;
            })
            ->rawColumns(['breakdown'])
            ->make(true);
    }


    public function onlineSales(){
          return view('sales.online');
    }

    public function onlineSalesData(){

    }
}
