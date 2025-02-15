<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Product;
use App\Models\Supplier;
use Box\Spout\Writer\Common\Creator\Style\StyleBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Barryvdh\DomPDF\Facade\Pdf;


class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->can('view-barang_masuk')) {
            abort(403);
        }
        $data = $this->data;
        $data['barang_masuk'] = Inbound::orderBy('created_at', 'desc')->get();
        return view('main.barang-masuk.data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('add-barang_masuk')) {
            abort(403);
        }
        $data = $this->data;

        $data['suppliers'] = Supplier::all();
        $data['products'] = Product::where('is_active', 1)->get();

        return view('main.barang-masuk.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->can('add-barang_masuk')) {
            abort(403);
        }
        // try {

        $request->validate([
            'tgl_masuk' => 'required',
            'supplier' => 'required',
            'produk' => 'required',
            "qty" => 'required',
        ]);

        $totalPayment = 0;

        $user = Auth::user();
        $newInbound = Inbound::create([
            'inbound_date' => $request->tgl_masuk,
            'supplier_id' => $request->supplier,
            'user_id' => $user->id,
            'note' => $request->keterangan ?? null,
        ]);

        $details = [];

        foreach ($request->produk as $key => $value) {
            $product = Product::find($value);

            $details[] = [
                'inbound_id' => $newInbound->id,
                'product_id' => $value,
                'note' => $request->keterangan_produk[$key] ?? null,
                'quantity' => $request->qty[$key],
                'buy_price' => $product->buy_price,
            ];


            // update product stock by $request->qty[$key]
            $product->stock += $request->qty[$key];
            $product->save();

            $totalPayment += $request->qty[$key] * $product->buy_price;
        }

        if (count($details) > 0) {
            foreach ($details as $detail) {
                InboundDetail::create($detail);
            }
        }

        $newInbound->total_payment = $totalPayment;
        $newInbound->save();

        return redirect('barang-masuk')->with('success', 'Data barang masuk berhasil ditambahkan.');
        // } catch (\Throwable $th) {
        //     Log::error($th);
        //     return redirect('barang-masuk/create')->with('error', 'Data barang masuk gagal ditambahkan.');
        // }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!auth()->user()->can('view-barang_masuk')) {
            abort(403);
        }
        $data = $this->data;
        $data['data'] = Inbound::find($id);
        return view('main.barang-masuk.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        if (!auth()->user()->can('edit-barang_masuk')) {
            abort(403);
        }
        $data = $this->data;

        $data['suppliers'] = Supplier::all();
        $data['products'] = Product::where('is_active', 1)->get();
        $data['data'] = Inbound::find($id);

        return view('main.barang-masuk.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        if (!auth()->user()->can('edit-barang_masuk')) {
            abort(403);
        }
        try {
            $request->validate([
                'tgl_masuk' => 'required',
                'supplier' => 'required',
                'produk' => 'required',
                'qty' => 'required',
            ]);

            $user = Auth::user();
            $inbound = Inbound::findOrFail($id);

            // Update inbound record
            $inbound->inbound_date = $request->tgl_masuk;
            $inbound->supplier_id = $request->supplier;
            $inbound->user_id = $user->id;
            $inbound->note = $request->keterangan ?? null;

            $details = [];
            foreach ($request->produk as $key => $value) {
                $details[] = [
                    'id' => $request->detail_id[$key] ?? null,
                    'inbound_id' => $inbound->id,
                    'product_id' => $value,
                    'note' => $request->keterangan_produk[$key] ?? null,
                    'quantity' => $request->qty[$key],
                ];
            }

            // Extract valid detail IDs (remove null values)
            $detailIds = array_filter(array_column($details, 'id'));

            // Delete missing details from DB
            if (!empty($detailIds)) {
                $deleteDetailList = InboundDetail::where('inbound_id', $inbound->id)
                    ->whereNotIn('id', $detailIds)->get();
            } else {
                // If all details are removed, delete all related records
                $deleteDetailList = InboundDetail::where('inbound_id', $inbound->id)->get();
            }

            foreach ($deleteDetailList as $deleteDetail) {
                // check is there OutboundDetail record related to this $deleteDetail->product after this detail created_at
                $relatedOutboundDetail = $deleteDetail->product->outboundDetails()
                    ->where('created_at', '>', $deleteDetail->created_at)->first();
                // if there is, then rollback the delete process
                if ($relatedOutboundDetail) {
                    return redirect('barang-masuk')->with('error', 'Data barang masuk gagal diubah. Produk ' . $deleteDetail->product->name . ' telah digunakan pada barang keluar.');
                } else {
                    // decrease product stock by $deleteDetail->quantity
                    $product = Product::find($deleteDetail->product_id);
                    if ($product) {
                        $product->stock -= $deleteDetail->quantity;
                        $product->save();
                    }
                }
            }

            // Update or insert new details
            foreach ($details as $detail) {
                if (!empty($detail['id'])) {
                    $existingDetail = InboundDetail::find($detail['id']);
                    // store old quantity
                    $oldQuantity = $existingDetail->quantity;
                    $newQuantity = $detail['quantity'];

                    $stockDiff = $newQuantity - $oldQuantity;

                    if ($existingDetail) {
                        $existingDetail->update($detail);
                    }

                    // update product stock by $stockDiff
                    $product = Product::find($detail['product_id']);
                    if ($product) {
                        $product->stock += $stockDiff;
                        $product->save();
                    }
                } else {
                    InboundDetail::create($detail);

                    // update product stock by $detail['quantity']
                    $product = Product::find($detail['product_id']);
                    if ($product) {
                        $product->stock += $detail['quantity'];
                        $product->save();
                    }
                }
            }

            $inbound->save();

            return redirect('barang-masuk')->with('success', 'Data barang masuk berhasil diubah.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect('barang-masuk/create')->with('error', 'Data barang masuk gagal diubah.');
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->can('delete-barang_masuk')) {
            abort(403);
        }
        try {
            // delete details
            $inboundDetails = InboundDetail::where('inbound_id', $id)->get();
            foreach ($inboundDetails as $inboundDetail) {
                $product = Product::find($inboundDetail->product_id);
                if ($product) {
                    $product->stock = $product->stock + $inboundDetail->quantity;
                    $product->save();
                }
                $inboundDetail->delete();
            }
            $inbound = Inbound::find($id);
            $inbound->delete();

            return redirect('barang-masuk')->with('success', 'Data barang masuk berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect('barang-masuk')->with('error', 'Data barang masuk gagal dihapus.');
        }
    }

    public function report(Request $request)
    {
        if (!auth()->user()->can('view-barang_masuk')) {
            abort(403);
        }
        $startDate = ($request->startDate ?? date('Y-m-d'));
        $endDate = ($request->endDate ?? date('Y-m-d'));

        $date = $startDate . ' - ' . $endDate;
        $data = $this->data;
        $data['searchDate'] = $date;
        $data['startDate'] = $startDate;
        $data['endDate'] = $endDate;

        $startDate = $startDate . ' 00:00:00';
        $endDate = $endDate . ' 23:59:59';
        $data['barang_masuk'] = Inbound::where('inbound_date', '>=', $startDate)
            ->where('inbound_date', '<=', $endDate)->orderBy('created_at', 'desc')
            ->get();

        $data['detail_barang_masuk'] = InboundDetail::whereHas('inbound', function ($query) use ($startDate, $endDate) {
            $query->where('inbound_date', '>=', $startDate)
                ->where('inbound_date', '<=', $endDate);
        })->get();
        return view('main.barang-masuk.report', $data);
    }

    public function export(Request $request)
    {
        if (!auth()->user()->can('view-barang_masuk')) {
            abort(403);
        }
        $startDate = $request->query('startDate') . ' 00:00:00';
        $endDate = $request->query('endDate') . ' 23:59:59';

        $data = InboundDetail::whereHas('inbound', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('inbound_date', [$startDate, $endDate]);
        })->orderBy('created_at', 'desc')->get();

        // timestamp for the file name
        $timestamp = date('YmdHis');

        // Create a writer instance for XLSX
        $writer = WriterEntityFactory::createXLSXWriter();
        $writer->openToBrowser('barang-masuk_' . $timestamp . '.xlsx'); // Sends the file directly to the browser

        // Create a bold style for the header row
        $headerStyle = (new StyleBuilder())->setFontBold()->build();

        // Define the header row
        $headerRow = WriterEntityFactory::createRowFromArray([
            'No',
            'Nama Produk',
            'Quantity',
            'Tanggal Masuk',
            'Supplier',
            'Petugas',
            'Total',
            'Catatan'
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
                $item->inbound->inbound_date ?? 'N/A',
                $item->inbound->supplier->name ?? 'N/A',
                $item->inbound->user->name ?? 'N/A',
                number_format($item->buy_price * $item->quantity, 0, ',', '.') ?? 'N/A',
                $item->inbound->note ?? 'N/A',
            ]);
            $writer->addRow($row);
        }

        // Close the writer to finalize the file
        $writer->close();
    }

    public function print(Request $request)
    {
        if (!auth()->user()->can('view-barang_masuk')) {
            abort(403);
        }
        $startDate = $request->query('startDate') . ' 00:00:00';
        $endDate = $request->query('endDate') . ' 23:59:59';

        $data = InboundDetail::whereHas('inbound', function ($query) use ($startDate, $endDate) {
            $query->whereBetween('inbound_date', [$startDate, $endDate]);
        })->orderBy('created_at', 'desc')->get();

        // Format the data for printing
        $formattedData = $data->map(function ($item, $index) {
            return [
                'No' => $index + 1,
                'Nama Produk' => $item->product->name ?? 'N/A',
                'Quantity' => number_format($item->quantity, 0, ',', '.'),
                'Tanggal Masuk' => $item->inbound->inbound_date ?? 'N/A',
                'Supplier' => $item->inbound->supplier->name ?? 'N/A',
                'Petugas' => $item->inbound->user->name ?? 'N/A',
                'Total' => number_format($item->quantity * $item->buy_price, 0, ',', '.') ?? 'N/A',
                'Catatan' => $item->inbound->note ?? 'N/A',
            ];
        });

        // Generate PDF
        $pdf = Pdf::loadView('print.barang_masuk', [
            'data' => $formattedData,
            'startDate' => $startDate,
            'endDate' => $endDate,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream('Laporan-Barang-Masuk.pdf'); // Open in browser
    }
}
