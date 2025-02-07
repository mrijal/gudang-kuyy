<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->data;
        $data['suppliers'] = Supplier::all();
        return view('main.supplier.data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('main.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'contact' => 'required|string'
        ]);

        Supplier::create([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact
        ]);

        return redirect('supplier')->with('success', 'Data supplier berhasil ditambahkan.');
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
        $supplier = Supplier::find($id);

        $data = $this->data;
        $data['data'] = $supplier;

        return view('main.supplier.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'address' => 'required|string',
            'contact' => 'required|string'
        ]);

        $supplier = Supplier::find($id);
        $supplier->update([
            'name' => $request->name,
            'address' => $request->address,
            'contact' => $request->contact
        ]);

        return redirect('supplier')->with('success', 'Data supplier berhasil diubah.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $supplier = Supplier::find($id);
        $supplier->delete();

        return redirect('supplier')->with('success', 'Data supplier berhasil dihapus.');
    }
}
