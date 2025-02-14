@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <form class="card-body p-4" id="form" action="{{url('role')}}" method="post">
          @csrf
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Tambah Role</h2>
          @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
          @endif

          @if (session('success'))
              <div class="alert alert-success">
                  {{ session('success') }}
              </div>
          @endif
        </div>
        <div class="container-fluid mb-3"> 
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="name">Nama Role</label>
                <input type="text" class="form-control" id="name" name="name">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="permissions" class="form-label">Assign Permissions</label>
                <select name="permissions[]" id="permissions" class="form-control" multiple>
                    @foreach($categories as $category => $permissionsArray)
                        <optgroup label="{{ $category }}">
                            @foreach($permissionsArray as $permission)
                                <option value="{{ $permission->name }}">{{ ucfirst(str_replace('-', ' ', $permission->name)) }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
  <script>
      $(document).ready(function() {
          $('#permissions').select2({
              placeholder: 'Select Permissions',
              allowClear: true
          });
      });
  </script>
    <script>

      // submit form
      $('#submitButton').click(function(){
        $('#form').submit();
      });
      
    </script>
@endpush