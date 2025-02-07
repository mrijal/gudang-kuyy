<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use App\Models\OutboundDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->data;
        $data['barang_keluar'] = Outbound::all();
        return view('main.barang-keluar.data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->data;
        $data['products'] = Product::all();

        return view('main.barang-keluar.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tgl_keluar' => 'required',
            'produk' => 'required',
            'qty' => 'required',
            'payment_method' => 'required',
            'total_payment' => 'required',
            'status' => 'required',
        ]);

        dd($request->all());

        $payment_history = [];
        $payment_history[] = [
            'payment_date' => $request->tgl_keluar,
            'payment_method' => $request->payment_method,
            'total_payment' => $request->total_payment,
        ];

        $payment_history = json_encode($payment_history);

        $user = Auth::user();
        $newOutbound = Outbound::create([
            'outbound_date' => $request->tgl_keluar,
            'user_id' => $user->id,
            'customer_name' => $request->customer_name ?? null,
            'note' => $request->note ?? null,
            'shipping_address' => $request->shipping_address ?? null,
            'shipping_fee' => $request->shipping_fee ?? null,
            'shipping_method' => $request->shipping_method ?? null,
            'discount' => $request->discount ?? null,
            'payment_method' => $request->payment_method,
            'total_payment' => $request->total_payment,
            'payment_history' => $payment_history,
            'status' => $request->status,
        ]);

        foreach ($request->produk as $key => $value) {
            OutboundDetail::create([
                'outbound_id' => $newOutbound->id,
                'product_id' => $value,
                'quantity' => $request->qty[$key],
                'discount' => $request->discount[$key] ?? null,
                'price_per_unit' => $request->price_per_unit[$key] ?? null,
                'note' => $request->note_produk[$key] ?? null,
            ]);
        }

        return redirect('barang-keluar')->with('success', 'Data barang keluar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
