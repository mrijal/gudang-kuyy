<?php

namespace App\Http\Controllers;

use App\Models\Inbound;
use App\Models\InboundDetail;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BarangMasukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->data;
        $data['barang_masuk'] = Inbound::all();
        return view('main.barang-masuk.data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = $this->data;

        $data['suppliers'] = Supplier::all();
        $data['products'] = Product::all();

        return view('main.barang-masuk.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {

            $request->validate([
                'tgl_masuk' => 'required',
                'supplier' => 'required',
                'produk' => 'required',
                "qty" => 'required',
            ]);

            $user = Auth::user();
            $newInbound = Inbound::create([
                'inbound_date' => $request->tgl_masuk,
                'supplier_id' => $request->supplier,
                'user_id' => $user->id,
                'note' => $request->keterangan ?? null,
            ]);

            $details = [];

            foreach ($request->produk as $key => $value) {
                $details[] = [
                    'inbound_id' => $newInbound->id,
                    'product_id' => $value,
                    'note' => $request->keterangan_produk[$key] ?? null,
                    'quantity' => $request->qty[$key],
                ];
            }

            if (count($details) > 0) {
                foreach ($details as $detail) {
                    InboundDetail::create($detail);
                }
            }

            return redirect('barang-masuk')->with('success', 'Data barang masuk berhasil ditambahkan.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect('barang-masuk/create')->with('error', 'Data barang masuk gagal ditambahkan.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->data;
        $data['data'] = Inbound::find($id);
        return view('main.barang-masuk.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = $this->data;

        $data['suppliers'] = Supplier::all();
        $data['products'] = Product::all();
        $data['data'] = Inbound::find($id);

        return view('main.barang-masuk.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
                    $deleteDetail->product->stock -= $deleteDetail->quantity;
                    $deleteDetail->product->save();
                    $deleteDetail->delete();
                }
            }

            // Update or insert new details
            foreach ($details as $detail) {
                if (!empty($detail['id'])) {
                    $existingDetail = InboundDetail::find($detail['id']);
                    if ($existingDetail) {
                        $existingDetail->update($detail);
                    }
                } else {
                    InboundDetail::create($detail);
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
        try {
            $inbound = Inbound::find($id);
            // delete details
            InboundDetail::where('inbound_id', $inbound->id)->delete();
            $inbound->delete();
            return redirect('barang-masuk')->with('success', 'Data barang masuk berhasil dihapus.');
        } catch (\Throwable $th) {
            Log::error($th);
            return redirect('barang-masuk')->with('error', 'Data barang masuk gagal dihapus.');
        }
    }
}
