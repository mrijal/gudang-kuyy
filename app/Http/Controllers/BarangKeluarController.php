<?php

namespace App\Http\Controllers;

use App\Models\Outbound;
use App\Models\OutboundDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangKeluarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view-barang_keluar')) {
            abort(403);
        }
        $data = $this->data;
        $data['barang_keluar'] = Outbound::all();

        return view('main.barang-keluar.data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('add-barang_keluar')) {
            abort(403);
        }
        $data = $this->data;
        $data['products'] = Product::where('is_active', 1)->get();

        return view('main.barang-keluar.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('add-barang_keluar')) {
            abort(403);
        }
        $request->validate([
            'tgl_keluar' => 'required',
            'produk' => 'required',
            'qty' => 'required',
            'payment_method' => 'required',
            'total_payment' => 'required',
        ]);

        $user = Auth::user();
        $newOutbound = Outbound::create([
            'outbound_date' => $request->tgl_keluar,
            'user_id' => $user->id,
            'customer_name' => $request->customer ?? null,
            'keterangan' => $request->note ?? null,
            'shipping_address' => $request->shipping_address ?? null,
            'shipping_fee' => $request->shipping_fee ?? null,
            'shipping_method' => $request->shipping_method ?? null,
            'discount' => $request->discount ?? null,
            'payment_method' => $request->payment_method,
            'total_payment' => $request->total_payment,
            'status' => "pending",
        ]);

        foreach ($request->produk as $key => $value) {
            OutboundDetail::create([
                'outbound_id' => $newOutbound->id,
                'product_id' => $value,
                'quantity' => $request->qty[$key],
                'discount' => $request->discount[$key] ?? null,
                'price_per_unit' => $request->harga_satuan[$key] ?? null,
                'note' => $request->note_produk[$key] ?? null,
            ]);

            // Update stock
            $product = Product::find($value);
            if ($product) {
                $product->stock = $product->stock - $request->qty[$key];
                $product->save();
            }
        }

        return redirect('barang-keluar')->with('success', 'Data barang keluar berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->can('view-barang_keluar')) {
            abort(403);
        }
        $data = $this->data;
        $data['barang_keluar'] = Outbound::find($id);
        $data['products'] = Product::where('is_active', 1)->get();
        return view('main.barang-keluar.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->can('edit-barang_keluar')) {
            abort(403);
        }
        $data = $this->data;

        $data['products'] = Product::where('is_active', 1)->get();
        $data['barang_keluar'] = Outbound::find($id);

        return view('main.barang-keluar.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('edit-barang_keluar')) {
            abort(403);
        }
        try {
            $request->validate([
                'tgl_keluar' => 'required',
                'produk' => 'required',
                'qty' => 'required',
                'payment_method' => 'required',
                'total_payment' => 'required',
                // 'status' => 'required',
            ]);

            // $payment_history = [];
            // $payment_history[] = [
            //     'payment_date' => $request->tgl_keluar,
            //     'payment_method' => $request->payment_method,
            //     'total_payment' => $request->total_payment,
            // ];

            // $payment_history = json_encode($payment_history);

            $user = Auth::user();
            $outbound = Outbound::find($id);
            $outbound->outbound_date = $request->tgl_keluar;
            $outbound->user_id = $user->id;
            $outbound->customer_name = $request->customer ?? null;
            $outbound->note = $request->keterangan ?? null;
            $outbound->shipping_address = $request->shipping_address ?? null;
            $outbound->shipping_fee = $request->shipping_fee ?? null;
            $outbound->shipping_method = $request->shipping_method ?? null;
            $outbound->discount = $request->discount ?? null;
            $outbound->payment_method = $request->payment_method;
            $outbound->total_payment = $request->total_payment;
            // $outbound->payment_history = $payment_history;
            // $outbound->status = $request->status;
            $outbound->save();

            foreach ($request->produk as $key => $value) {
                $outboundDetail = OutboundDetail::where('outbound_id', $id)->where('product_id', $value)->first();
                if ($outboundDetail) {
                    // store the old quantity
                    $oldQuantity = $outboundDetail->quantity;
                    $newQuantity = $request->qty[$key];

                    $stockDiff = $oldQuantity - $newQuantity;

                    // Update stock
                    $product = Product::find($value);
                    if ($product) {
                        $product->stock = $product->stock + $stockDiff;
                        $product->save();
                    }

                    $outboundDetail->quantity = $request->qty[$key];
                    $outboundDetail->discount = $request->discount[$key] ?? null;
                    $outboundDetail->price_per_unit = $request->price_per_unit[$key] ?? null;
                    $outboundDetail->note = $request->note_produk[$key] ?? null;
                    $outboundDetail->save();
                } else {
                    OutboundDetail::create([
                        'outbound_id' => $id,
                        'product_id' => $value,
                        'quantity' => $request->qty[$key],
                        'discount' => $request->discount[$key] ?? null,
                        'price_per_unit' => $request->price_per_unit[$key] ?? null,
                        'note' => $request->note_produk[$key] ?? null,
                    ]);

                    // Update stock
                    $product = Product::find($value);
                    if ($product) {
                        $product->stock = $product->stock - $request->qty[$key];
                        $product->save();
                    }
                }
            }

            return redirect('barang-keluar')->with('success', 'Data barang keluar berhasil diubah.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect('barang-keluar')->with('error', 'Data barang keluar gagal diubah.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->can('delete-barang_keluar')) {
            abort(403);
        }
        try {

            $outboundDetails = OutboundDetail::where('outbound_id', $id)->get();
            foreach ($outboundDetails as $outboundDetail) {
                $product = Product::find($outboundDetail->product_id);
                if ($product) {
                    $product->stock = $product->stock + $outboundDetail->quantity;
                    $product->save();
                }
                $outboundDetail->delete();
            }
            $outbound = Outbound::find($id);
            $outbound->delete();

            return redirect('barang-keluar')->with('success', 'Data barang keluar berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect('barang-keluar')->with('error', 'Data barang keluar gagal dihapus.');
        }
    }

    public function report(Request $request)
    {
        $startDate = $request->startDate ?? date('Y-m-d');
        $endDate = $request->endDate ?? date('Y-m-d');
        $date = $startDate . ' - ' . $endDate;
        $data = $this->data;
        $data['searchDate'] = $date;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;
        $startDate = $startDate . ' 00:00:00';
        $endDate = $endDate . ' 23:59:59';
        $data['barang_keluar'] = Outbound::where('outbound_date', '>=', $startDate)
            ->where('outbound_date', '<=', $endDate)
            ->get();
        return view('main.barang-keluar.report', $data);
    }

    public function export(Request $request)
    {
        $startDate = $request->query('startDate') . ' 00:00:00';
        $endDate = $request->query('endDate') . ' 23:59:59';

        $data = OutboundDetail::whereHas('outbound', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('outbound_date', [$startDate, $endDate]);
        })->get();

        // timestamp for the file name
        $timestamp = date('YmdHis');

        // Create a writer instance for XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser('barang-keluar_' . $timestamp . '.xlsx'); // Sends the file directly to the browser

        // Create a bold style for the header row
        $headerStyle = (new StyleBuilder())->setFontBold()->build();

        // Define the header row
        $headerRow = WriterEntityFactory::createRowFromArray([
            'No',
            'Nama Produk',
            'Harga Produk',
            'Quantity',
            'Tanggal Keluar',
            'Customer',
            'Petugas',
            'Catatan',
            'Total Harga',
        ], $headerStyle);

        // Add header row to the file
        $writer->addRow($headerRow);

        // Add data rows
        $no = 1;
        foreach ($data as $item) {
            $row = WriterEntityFactory::createRowFromArray([
                $no++,
                $item->product->name ?? 'N/A',
                number_format($item->quantity, 0, ',', '.'),
                number_format($item->price_per_unit, 0, ',', '.'),
                $item->outbound->outbound_date ?? 'N/A',
                $item->outbound->customer_name ?? 'N/A',
                $item->outbound->user->name ?? 'N/A',
                $item->outbound->note ?? 'N/A',
                number_format($item->quantity * $item->price_per_unit, 0, ',', '.'), // Total price
            ]);
            $writer->addRow($row);
        }

        // Close the writer to finalize the file
        $writer->close();
    }

    public function print(Request $request)
    {
        $startDate = $request->query('startDate') . ' 00:00:00';
        $endDate = $request->query('endDate') . ' 23:59:59';

        $data = OutboundDetail::whereHas('outbound', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('outbound_date', [$startDate, $endDate]);
        })->get();

        // Format the data for printing
        $formattedData = $data->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Nama Produk' => $item->product->name ?? 'N/A',
                'Quantity' => number_format($item->quantity, 0, ',', '.'),
                'Harga Produk' => number_format($item->price_per_unit, 0, ',', '.'),
                'Tanggal Masuk' => $item->outbound->outbound_date ?? 'N/A',
                'Supplier' => $item->outbound->customer_name ?? 'N/A',
                'Petugas' => $item->outbound->user->name ?? 'N/A',
                'Catatan' => $item->outbound->note ?? 'N/A',
                'Total Harga' => number_format($item->quantity * $item->price_per_unit, 0, ',', '.'), // Total price
            ];
        });

        // Generate PDF
        $pdf = Pdf::loadView('print.barang_keluar', [
            'data' => $formattedData,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Barang-Keluar.pdf'); // Open in browser
    }
}
