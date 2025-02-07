@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <form class="card-body p-4" id="form" action="{{url('supplier/' . $data->id)}}" method="post">
        @method('PUT')
          @csrf
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Edit Supplier</h2>
        </div>
        <div class="container-fluid mb-3"> 
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="name">Nama Supplier</label>
                <input type="text" class="form-control" id="name" name="name" value="{{$data->name}}">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="supplier">Contact</label>
                <input type="text" class="form-control" id="contact" name="contact" value="{{$data->contact}}">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="petugas">Alamat</label>
                <textarea name="address" id="address" cols="30" rows="2" class="form-control">{{$data->address}}</textarea>
              </div>
            </div>
          </div>
          <div class="row">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
      // submit form
      $('#submitButton').click(function(){
        $('#form').submit();
      });
      
    </script>
@endpush