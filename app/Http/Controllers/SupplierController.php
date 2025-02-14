<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view-supplier')) {
            abort(403);
        }
        $data = $this->data;
        $data['suppliers'] = Supplier::all();
        return view('main.supplier.data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('add-supplier')) {
            abort(403);
        }
        $data = $this->data;
        return view('main.supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('add-supplier')) {
            abort(403);
        }
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
        if (!auth()->user()->can('edit-supplier')) {
            abort(403);
        }
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
        if (!auth()->user()->can('edit-supplier')) {
            abort(403);
        }
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
        if (!auth()->user()->can('delete-supplier')) {
            abort(403);
        }
        $supplier = Supplier::find($id);
        $supplier->delete();

        return redirect('supplier')->with('success', 'Data supplier berhasil dihapus.');
    }
}
