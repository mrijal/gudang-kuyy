@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <form class="card-body p-4" id="form" action="{{url('supplier')}}" method="post">
          @csrf
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Tambah Supplier</h2>
        </div>
        <div class="container-fluid mb-3"> 
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="name">Nama Supplier</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="supplier">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="petugas">Alamat</label>
                <textarea name="address" id="address" cols="30" rows="2" class="form-control"></textarea>
              </div>
            </div>
          </div>
          <div class="row mt-3">
            <div class="col-lg-12 d-flex justify-content-end">
              <button type="button" id="submitButton" class="btn btn-primary">Simpan</button>
            </div>
          </div>
        </div>  
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
    <script>

      // submit form
      $('#submitButton').click(function(){
        $('#form').submit();
      });
      
    </script>
@endpush