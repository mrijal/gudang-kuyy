@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <form class="card-body p-4" id="form" action="{{url('user/'. $user->id)}}" method="post">
          @csrf
          @method('PUT') {{-- Use PUT method for updating --}}
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Edit User</h2>
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
          <div class="row row-gap-3">
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="name">Nama User</label>
                <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="{{$user->email}}">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password">
                <span class="text-muted fs-2">(Kosongkan Jika Password Tetap)</span>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label for="role" class="form-label">Assign role</label>
                <select name="role" id="role" class="form-control">
                    @foreach($roles as $role)
                        @if ($user->hasRole($role->name))
                            <option value="{{ $role->name }}" selected>{{ $role->name }}</option>
                        @else
                            <option value="{{ $role->name }}">{{ $role->name }}</option>
                        @endif
                    @endforeach
                </select>
              </div>
            </div>
            <div class="col-lg-4 d-flex align-items-end">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" @if($user->is_active == 1) checked @endif id="is_active" name="is_active">
                    <label class="form-check-label" for="is_active">
                        Is Active ?
                    </label>
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
          $('#role').select2();
      });
  </script>
    <script>

      // submit form
      $('#submitButton').click(function(){
        $('#form').submit();
      });
      
    </script>
@endpush