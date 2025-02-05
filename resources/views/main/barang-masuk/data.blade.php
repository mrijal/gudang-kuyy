@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <h2 class="fw-semibold mb-4">Barang Masuk</h2>
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
                    <h6 class="fw-semibold mb-1">Sunil Joshi</h6>
                    <span class="fw-normal">Web Designer</span>                          
                </td>
                <td class="border-bottom-0">
                  <p class="mb-0 fw-normal">Elite Admin</p>
                </td>
                <td class="border-bottom-0">
                  <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary rounded-3 fw-semibold">Low</span>
                  </div>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">$3.9</h6>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">$3.9</h6>
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