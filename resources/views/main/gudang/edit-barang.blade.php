@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <form class="card-body p-4" id="form" action="{{url('product/'. $product->id)}}" method="post" enctype="multipart/form-data">
          @method('PUT')
          @csrf
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Edit Produk</h2>
        </div>
        <div class="container-fluid mb-3"> 
          <div class="row">
            <div class="col-lg-6">
              <div class="container-fluid">
                <div class="row row-gap-2">
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-label" for="name">Nama Product</label>
                      <input type="text" class="form-control" id="name" name="name"value="{{$product->name}}">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-label" for="description">Deskripsi</label>
                      <textarea name="description" id="description" cols="30" rows="2" class="form-control">{{$product->description}}</textarea>
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-label" for="buy_price">Harga Beli</label>
                      <input type="number" class="form-control" id="buy_price" name="buy_price" value="{{$product->buy_price}}">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-label" for="sell_price">Harga Jual</label>
                      <input type="number" class="form-control" id="sell_price" name="sell_price" value="{{$product->sell_price}}">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-label" for="stock">Stock Awal</label>
                      <input type="number" class="form-control" id="stock" name="stock" value="0" value="{{$product->stock}}">
                    </div>
                  </div>
                  <div class="col-lg-12">
                    <div class="form-group">
                      <label class="form-label" for="stock">Aktif ?</label>
                      <select name="status" id="status" class="form-control">
                        <option value="1" @if ($product->status == 1) selected @endif>Aktif</option>
                        <option value="0" @if ($product->status == 0) selected @endif>Tidak Aktif</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-label">Upload Image</label>
                <div class="image-upload-wrapper d-flex align-items-center justify-content-start">
                  <input type="hidden" name="old_image" value="{{$product->image}}">
                  <input type="file" id="imageUpload" accept="image/*" hidden name="image">
                  <div class="upload-box" onclick="document.getElementById('imageUpload').click()">
                    <img id="previewImage" src="@if($product->image) {{ asset('storage/' . $product->image) }} @else https://placehold.co/600x400?text=Click+Here @endif" class="img-fluid" alt="Upload Image">
                  </div>
                </div>
                <span class="text-secondary fs-2">(Click untuk Ganti Gambar)</span>
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

@push('styles')
    
  <style>
    .image-upload-wrapper {
      width: 100%;
      display: flex;
      justify-content: center;
    }
    .upload-box {
      width: 200px;
      height: 200px;
      border: 2px dashed #ccc;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      border-radius: 10px;
      overflow: hidden;
    }
    .upload-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }
  </style>
@endpush
@push('scripts')
  <script>

    // submit form
    $('#submitButton').click(function(){
      $('#form').submit();
    });
    
  </script>

  <script>
    document.getElementById('imageUpload').addEventListener('change', function(event) {
      const file = event.target.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
          document.getElementById('previewImage').src = e.target.result;
        }
        reader.readAsDataURL(file);
        // old image value reset
        document.querySelector('input[name="old_image"]').value = '';
      }
    });
  </script>
@endpush