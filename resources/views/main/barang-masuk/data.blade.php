@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Barang Masuk</h2>
          <a href="{{url('barang-masuk/create')}}" class="btn btn-primary">Tambah Data</a>
        </div>
        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle">
            <thead class="text-dark fs-4">
              <tr>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">No</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Tgl Masuk</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Petugas</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Jumlah Barang</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Catatan</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Opsi</h6>
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($barang_masuk as $item)
              @php
                  // format inbound_date to 01 Jan 2024
                  $date = date_create($item->inbound_date);
                  $date = date_format($date, "d M Y");

                  // count how much product in this inbound
                  $total_product = 0;
                  foreach ($item->details as $data) {
                    $total_product += $data->quantity;
                  }
              @endphp
              <tr>
                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-1">{{$date}}</h6>                     
                </td>
                <td class="border-bottom-0">
                  <p class="mb-0 fw-normal">{{$item->user ? $item->user->name : null}}</p>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">{{$total_product}}</h6>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">{{$item->note}}</h6>
                </td>
                <td class="border-bottom-0">
                  <div class="d-flex align-items-center gap-2">
                    <a href="{{url('barang-masuk/'. $item->id . '/edit')}}" class="btn btn-success" title="Edit Data" ><i class="ti ti-edit"></i></a>
                    <a href="{{url('barang-masuk/'. $item->id)}}" class="btn btn-primary" title="Detail Data" ><i class="ti ti-eye"></i></a>
                    <form action="{{url('barang-masuk/'. $item->id)}}" method="POST">
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
        }
        
        </script>
@endpush