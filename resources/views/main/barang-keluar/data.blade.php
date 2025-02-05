@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <h3 class="card-title fw-semibold mb-4">Barang Keluar / Penjualan</h3>
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
                  <h6 class="fw-semibold mb-0">Nama Barang</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Jumlah</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Opsi</h6>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">1</h6></td>
                <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-1">01 Jan 2024</h6>                     
                </td>
                <td class="border-bottom-0">
                  <p class="mb-0 fw-normal">Admin Gudang</p>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">Mouse</h6>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">3</h6>
                </td>
                <td class="border-bottom-0">
                  <div class="d-flex align-items-center gap-2">
                    <a href="" class="btn btn-success" title="Edit Data" ><i class="ti ti-edit"></i></a>
                    <a href="" class="btn btn-primary" title="Detail Data" ><i class="ti ti-eye"></i></a>
                  </div>
                </td>
              </tr>           
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection