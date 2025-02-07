<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
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
        return view('main.barang-keluar.data');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->data;
        $data['products'] = Product::all();

        return view('main.barang-keluar.create');
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
        ]);

        $user = Auth::user();
        $newOutbound = Outbound::create([
            'outbound_date' => $request->tgl_keluar,
            'product_id' => $request->produk,
            'user_id' => $user->id,
            'qty' => $request->qty,
            'note' => $request->keterangan ?? null,
        ]);

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
