@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Laporan Stock Opname</h2>
        </div>
        <div class="d-flex gap-2 align-items-center justify-content-between">
          <div class="d-flex w-25">
            <input type="text" class="daterange form-control" value="{{$searchDate}}" />
          </div>
          <div class="d-flex gap-2">
            <a href="{{url('stock-opname/print')}}?startDate={{$startDate}}&endDate={{$endDate}}" class="btn btn-primary" target="_blank">Print Laporan</a>
            <a href="{{url('stock-opname/export')}}?startDate={{$endDate}}&endDate={{$endDate}}" class="btn btn-success" target="_blank">Export Excel</a>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle">
            <thead class="text-dark fs-4">
              <tr>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">No</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Tgl Opname</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Petugas</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Nama Barang</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Stock Awal</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Stock Opname</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Catatan</h6>
                </th>
              </tr>
            </thead>
            <tbody>
              @if ($opnames->isEmpty())
                <tr>
                  <td colspan="5" class="text-center">Data Tidak Tersedia untuk tanggal ini</td>
                </tr>
                  
              @endif
              @foreach ($opnames as $item)
                @php
                    // format inbound_date to 01 Jan 2024
                    $date = date_create($item->opname_date);
                    $date = date_format($date, "d M Y H:i:s");
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
                    <h6 class="fw-semibold mb-0 fs-4">{{$item->product->name}}</h6>
                  </td>
                  <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 fs-4">{{number_format($item->stock, 0, ',','.')}}</h6>
                  </td>
                  <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 fs-4">{{number_format($item->real_stock, 0, ',','.')}}</h6>
                  </td>
                  <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-0 fs-4">{{$item->note}}</h6>
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
        
      $(function() {
        
        $('.daterange').daterangepicker({
          opens: 'right',
          locale: {
            format: 'YYYY-MM-DD'
          }
        }, function(start, end, label) {
          // redirect to {{url('barang-masuk/report')}}?startDate=2024-01-01&endDate=2024-01-01
          window.location.href = "{{url('laporan-barang-masuk')}}?startDate=" + start.format('YYYY-MM-DD') + "&endDate=" + end.format('YYYY-MM-DD');
        });
      });
        
        </script>
@endpush