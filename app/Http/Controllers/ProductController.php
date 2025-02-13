<?php

namespace App\Http\Controllers;

use App\Models\Opname;
use App\Models\Product;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function stockOpnamePage()
    {
        $data = $this->data;
        $products = Product::all();
        $data['products'] = $products;
        return view('main.gudang.stock-opname', $data);
    }

    public function stockOpnameUpdate(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'real_stock' => 'required|integer|min:0',
            'note' => 'nullable|string',
        ]);

        $product = Product::findOrFail($request->product_id);

        // Simpan data stock opname
        $opname = Opname::create([
            'opname_date' => now(),
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'stock' => $product->stock,
            'real_stock' => $request->real_stock,
            'note' => $request->note,
        ]);

        // Update stok produk
        $product->update([
            'stock' => $request->real_stock
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Stock opname berhasil disimpan.',
            'data' => $opname
        ]);
    }

    public function stockOpnameReport(Request $request)
    {
        $startDate = $request->start_date ?? now()->subMonth()->format('Y-m-d');
        $endDate = $request->end_date ?? now()->format('Y-m-d');
        $date = $startDate . ' - ' . $endDate;
        $data = $this->data;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $data['searchDate'] = $date;
        $startDate = $startDate . ' 00:00:00';
        $endDate = $endDate . ' 23:59:59';
        $opnames = Opname::where('opname_date', '>=', $startDate)
            ->where('opname_date', '<=', $endDate)
            ->get();
        $data['opnames'] = $opnames;
        return view('main.gudang.report-opname', $data);
    }



    public function stockOpnameExport(Request $request)
    {
        try {
            $startDate = $request->query('startDate') . ' 00:00:00';
            $endDate = $request->query('endDate') . ' 23:59:59';

            $data = Opname::where('opname_date', '>=', $startDate)
                ->where('opname_date', '<=', $endDate)
                ->get();

            // timestamp for the file name
            $timestamp = date('YmdHis');

            // Create a writer instance for XLSX
            $writer = WriterEntityFactory::createXLSXWriter();
            $writer->openToBrowser('stock-opname_' . $timestamp . '.xlsx'); // Sends the file directly to the browser

            // Create a bold style for the header row
            $headerStyle = (new StyleBuilder())->setFontBold()->build();

            // Define the header row
            $headerRow = WriterEntityFactory::createRowFromArray([
                'No',
                'Tanggal Opname',
                'Petugas',
                'Nama Produk',
                'Stock Awal',
                'Stock Opname',
                'Catatan'
            ], $headerStyle);

            // Add header row to the file
            $writer->addRow($headerRow);

            // Add data rows
            $no = 1;
            foreach ($data as $item) {
                $date = date('d/m/Y H:i:s', strtotime($item->opname_date));
                $row = WriterEntityFactory::createRowFromArray([
                    $no++,
                    $date,
                    $item->user->name ?? 'N/A',
                    $item->product->name ?? 'N/A',
                    number_format($item->stock, 0, ',', '.'),
                    number_format($item->real_stock, 0, ',', '.'),
                    $item->note ?? 'N/A',
                ]);
                $writer->addRow($row);
            }

            // Close the writer to finalize the file
            $writer->close();
        } catch (\Throwable $th) {
            Log::error($th);
            return back()->withErrors(['error' => 'Failed to export data.']);
        }
    }

    public function stockOpnamePrint(Request $request)
    {
        $startDate = $request->query('startDate') . ' 00:00:00';
        $endDate = $request->query('endDate') . ' 23:59:59';

        $data = Opname::where('opname_date', '>=', $startDate)
            ->where('opname_date', '<=', $endDate)
            ->get();

        // Format the data for printing
        $formattedData = $data->map(function ($item, $index) {
            $date = date('d/m/Y H:i:s', strtotime($item->opname_date));
            return [
                'No' => $index + 1,
                'Tanggal Opname' => $date,
                'Petugas' => $item->user->name ?? 'N/A',
                'Nama Produk' => $item->product->name ?? 'N/A',
                'Stock Awal' => number_format($item->stock, 0, ',', '.'),
                'Stock Opname' => number_format($item->real_stock, 0, ',', '.'),
                'Catatan' => $item->note ?? 'N/A',
            ];
        });

        // Generate PDF
        $pdf = Pdf::loadView('print.stock_opname', [
            'data' => $formattedData,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Stock-Opname.pdf'); // Open in browser
    }
}
