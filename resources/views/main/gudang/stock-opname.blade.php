@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Stock Opname</h2>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered" id="dataTable">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Produk</th>
                <th>Stock Sistem</th>
                <th>Opname Terkahir</th>
                <th>Stock Real</th>
                <th>Catatan</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($products as $key => $product)
              <tr>
                <td>{{ $key + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->stock, 0, ',', '.') }}</td>
                @php
                    $lastOpname = App\Models\Opname::where('product_id', $product->id)->orderBy('opname_date', 'desc')->first();
                @endphp
                <td>{{ $lastOpname ? $lastOpname->opname_date : "-" }}</td>
                <td>
                  <input type="number" class="form-control real-stock" data-product-id="{{ $product->id }}" min="0">
                </td>
                <td>
                  <input type="text" class="form-control note" data-product-id="{{ $product->id }}" placeholder="Catatan (Opsional)">
                </td>
                <td>
                  <button class="btn btn-primary submit-opname" data-product-id="{{ $product->id }}">Submit</button>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>  
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).on('click', '.submit-opname', function() {
    let productId = $(this).data('product-id');
    let realStock = $('.real-stock[data-product-id="' + productId + '"]').val();
    let note = $('.note[data-product-id="' + productId + '"]').val();

    if (realStock === '') {
      alert('Stock Real tidak boleh kosong!');
      return;
    }

    $.ajax({
      url: "{{ route('stock.opname.store') }}",
      type: "POST",
      data: {
        _token: "{{ csrf_token() }}",
        product_id: productId,
        real_stock: realStock,
        note: note
      },
      success: function(response) {
        alert(response.message);
        location.reload();
      },
      error: function(xhr) {
        alert('Terjadi kesalahan, silakan coba lagi.');
      }
    });
  });
</script>
@endpush
