@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Detail Produk</h2>
        </div>
        <div class="container-fluid mb-3"> 
          <div class="row">
            <div class="col-lg-6">
              <table class="border-0 w-100 p-2">
                <tr>
                  <th class="p-3 fs-4 fw-bold">Nama Produk</th>
                  <th class="p-3 fs-4 fw-bold">:</th>
                  <td class="p-3 fs-4">{{$product->name}}</td>
                </tr>
                <tr>
                  <th class="p-3 fs-4 fw-bold">Deskripsi</th>
                  <th class="p-3 fs-4 fw-bold">:</th>
                  <td class="p-3 fs-4">{{$product->description}}</td>
                </tr>
                <tr>
                  <th class="p-3 fs-4 fw-bold">Harga Beli</th>
                  <th class="p-3 fs-4 fw-bold">:</th>
                  <td class="p-3 fs-4">{{number_format($product->buy_price, 0, ',','.')}}</td>
                </tr>
                <tr>
                  <th class="p-3 fs-4 fw-bold">Harga Jual</th>
                  <th class="p-3 fs-4 fw-bold">:</th>
                  <td class="p-3 fs-4">{{number_format($product->sell_price, 0, ',','.')}}</td>
                </tr>
                <tr>
                  <th class="p-3 fs-4 fw-bold">Stock</th>
                  <th class="p-3 fs-4 fw-bold">:</th>
                  <td class="p-3 fs-4">{{number_format($product->stock, 0, ',','.')}}</td>
                </tr>
                <tr>
                  <th class="p-3 fs-4 fw-bold">Status</th>
                  <th class="p-3 fs-4 fw-bold">:</th>
                  <td class="p-3 fs-4">{{$product->is_active == 1 ? 'Aktif' : 'Tidak Aktif'}}</td>
                </tr>
              </table>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-label">Upload Image</label>
                <div class="image-upload-wrapper d-flex align-items-center justify-content-start">
                  <input type="hidden" name="old_image" value="{{$product->image}}">
                  <input type="file" id="imageUpload" accept="image/*" hidden name="image">
                  <div class="upload-box">
                    <img id="previewImage" src="@if($product->image) {{ asset('storage/' . $product->image) }} @else https://placehold.co/600x400?text=Click+Here @endif" class="img-fluid" alt="Upload Image">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>  
      </div>
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