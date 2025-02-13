<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->data;
        $products = Product::all();
        $data['products'] = $products;
        return view('main.gudang.list-barang', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->data;
        return view('main.gudang.tambah-barang', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'sell_price' => 'required',
            'buy_price' => 'required',
            'status' => 'required',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->sell_price = $request->sell_price;
        $product->buy_price = $request->buy_price;
        $product->is_active = $request->status;
        $product->stock = 0;

        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $file = $request->file('image');

                // Generate a unique filename
                $filename = time() . '_' . $file->getClientOriginalName();

                // Store the file in 'products' inside the 'public' disk
                $path = $file->storeAs('products', $filename, 'public');

                // Save the file path in the database
                $product->image = $path;
            } else {
                return back()->withErrors(['image' => 'Uploaded file is not valid.']);
            }
        }


        if ($request->description) {
            $product->description = $request->description;
        }

        $product->save();

        return redirect()->route('product.index')->with('success', 'Product Baru Berhasil Dibuat');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->data;
        $product = Product::find($id);
        $data['product'] = $product;
        return view('main.gudang.detail-barang', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->data;
        $product = Product::find($id);
        $data['product'] = $product;
        return view('main.gudang.edit-barang', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'sell_price' => 'required',
            'buy_price' => 'required',
            'status' => 'required',
        ]);

        $product = Product::find($id);
        $product->name = $request->name;
        $product->sell_price = $request->sell_price;
        $product->buy_price = $request->buy_price;
        $product->is_active = $request->status;

        if (empty($request->old_image)) {
            if ($request->hasFile('image')) {
                if ($request->file('image')->isValid()) {
                    $file = $request->file('image');

                    // Generate a unique filename
                    $filename = time() . '_' . $file->getClientOriginalName();

                    // Store the file in 'products' inside the 'public' disk
                    $path = $file->storeAs('products', $filename, 'public');

                    // Save the file path in the database
                    $product->image = $path;
                } else {
                    return back()->withErrors(['image' => 'Uploaded file is not valid.']);
                }
            }
        }

        if ($request->description) {
            $product->description = $request->description;
        }

        $product->save();

        return redirect()->route('product.index')->with('success', 'Product Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::find($id);
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Product Berhasil Dihapus');
    }
}
