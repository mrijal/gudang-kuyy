@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">List Stock Gudang</h2>
          <a href="{{url('product/create')}}" class="btn btn-primary">Tambah Produk</a>
        </div>
        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle">
            <thead class="text-dark fs-4">
              <tr>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">No</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Gambar</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Nama Produk</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Harga Beli</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Harga Jual</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Stock</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Status</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Opsi</h6>
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($products as $item)
              <tr>
                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                <td class="border-bottom-0 text-center" width="10%">
                    @if ($item->image)
                        <img alt="Gambar {{$item->name}}" src="{{ asset('storage/' . $item->image) }}" class="img-fluid rounded" style="width: 75px; height: 75px;"> 
                    @else
                        <div class="">
                          <i class="ti ti-photo-x text-danger fw-bold fs-8"></i>
                        </div>
                    @endif
                </td>
                <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-1">{{$item->name}}</h6>                     
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">{{number_format($item->buy_price, 0, ',', '.')}}</h6>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">{{number_format($item->sell_price, 0, ',', '.')}}</h6>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">{{number_format($item->stock, 0, ',', '.')}}</h6>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">@if($item->is_active) <i class="ti ti-circle-check text-success"></i> @else <i class="ti ti-xbox-x text-danger"></i> @endif</h6>
                </td>
                <td class="border-bottom-0">
                  <div class="d-flex align-items-center gap-2">
                    <a href="{{url('product/'. $item->id)}}" class="btn btn-primary" title="Lihat Detail Data" ><i class="ti ti-eye"></i></a>
                    <a href="{{url('product/'. $item->id . '/edit')}}" class="btn btn-success" title="Edit Data" ><i class="ti ti-edit"></i></a>
                    <form action="{{url('product/'. $item->id)}}" method="POST">
                      @csrf
                      @method('DELETE')
                      <a href="javascript:void(0)" onclick="hapus(this)" class="btn btn-danger" title="Delete Data" ><i class="ti ti-trash"></i></a>
                    </form>
                  </div>
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
      function hapus(el) {
            Swal.fire({
                title: "Yakin Hapus?",
                text: "Data Detail ini akan terhapus!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yoi Bang!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $(el).closest('form').submit();
                }
            });
        }</script>

@endpush