@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">List Role</h2>
          <a href="{{url('role/create')}}" class="btn btn-primary">Tambah Role</a>
        </div>
        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle">
            <thead class="text-dark fs-4">
              <tr>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">No</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Nama</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Opsi</h6>
                </th>
              </tr>
            </thead>
            <tbody>
              @foreach ($roles as $item)
              <tr>
                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                <td class="border-bottom-0">
                    <h6 class="fw-semibold mb-1">{{$item->name}}</h6>                     
                </td>
                <td class="border-bottom-0">
                  <div class="d-flex align-items-center gap-2">
                    <a href="{{url('role/'. $item->id)}}" class="btn btn-primary" title="Lihat Detail Data" ><i class="ti ti-eye"></i></a>
                    @can('edit-role')
                    <a href="{{url('role/'. $item->id . '/edit')}}" class="btn btn-success" title="Edit Data" ><i class="ti ti-edit"></i></a>
                    @endcan
                    @can('delete-role')
                    <form action="{{url('role/'. $item->id)}}" method="POST">
                      @csrf
                      @method('DELETE')
                      <a href="javascript:void(0)" onclick="hapus(this)" class="btn btn-danger" title="Delete Data" ><i class="ti ti-trash"></i></a>
                    </form>
                    @endcan
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
                text: "Data ini akan terhapus!",
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