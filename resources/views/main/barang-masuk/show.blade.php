@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
          @csrf
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Edit Barang Masuk</h2>
        </div>
        <div class="container-fluid mb-3"> 
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="tgl_masuk">Tgl Masuk</label>
                <input type="date" class="form-control" id="tgl_masuk" name="tgl_masuk">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="supplier">Supplier</label>
                <select name="supplier" class="form-control" id="supplier">
                    <option value="{{$data->supplier->id}}">{{$data->supplier->id}}</option>
                </select>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="petugas">Petugas</label>
                <input type="text" class="form-control" id="petugas" name="petugas" value="{{Auth::user()->name}}" disabled>
              </div>
            </div>
          </div>
        </div>  
        
        <hr class="my-4">

        <div class="d-flex gap-3 justify-content-between align-items-center">
          <h3 class="fw-semibold mb-0 ">Detail Pesanan</h3>
          <a href="javascript:void(0)" id="add_detail" class="btn btn-primary p-1 px-2"><i class="ti ti-plus"></i></a>
        </div>

        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle" id="table_detail">
            <thead class="text-dark fs-4">
              <tr>
                <th class="border-bottom-0" width="5%">
                  <h6 class="fw-semibold mb-0">No</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Produk</h6>
                </th>
                {{-- <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Harga Satuan</h6>
                </th> --}}
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Note</h6>
                </th>
                <th class="border-bottom-0" width="15%">
                  <h6 class="fw-semibold mb-0">QTY</h6>
                </th>
                {{-- <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Total</h6>
                </th> --}}
                {{-- <th class="border-bottom-0" width="5%">
                  <h6 class="fw-semibold mb-0">Action</h6>
                </th> --}}
              </tr>
            </thead>
            <tbody>        
                @foreach ($data->details as $detail)
                @php
                    // dd($detail->product->name);
                @endphp 
                    <tr>
                        <input type="hidden" name="id[]" value="{{$detail->id}}">
                        <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                        <td class="border-bottom-0">
                            <input type="text" name="product[]" class="form-control text-start" value="{{$detail->product->name}}" readonly>
                        </td>
                        <td class="border-bottom-0">
                            <input type="text" name="note[]" value="{{$detail->note}}" class="form-control"readonly>
                        </td>
                        <td class="border-bottom-0">
                            <div class="d-flex gap-2 align-items-center justify-content-center">
                                {{-- <a href="javascript:void(0)" class="btn btn-danger" title="Kurang Qty" onclick="kurang(this)"><i class="ti ti-minus"></i></a> --}}
                                <input type="number" name="qty[]" class="form-control text-center" value="{{$detail->quantity}}"readonly>
                                {{-- <a href="javascript:void(0)" class="btn btn-primary" title="Tambah Qty" onclick="tambah(this)"><i class="ti ti-plus"></i></a> --}}
                            </div>
                        </td>
                        {{-- <td class="border-bottom-0">
                            <a href="javascript:void(0)" class="btn btn-danger" title="Hapus Data" onclick="hapus(this)"><i class="ti ti-trash"></i></a>
                        </td> --}}
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Keterangan</h6></td>
                    <td colspan="3" class="border-bottom-0 p-0"><textarea name="keterangan" class="form-control border-1" readonly></textarea></td>
                </tr>
                {{-- save --}}
                <tr>
                    <td colspan="5" class="text-end border-bottom-0 p-2">
                    {{-- <button type="button" class="btn btn-primary" id="submitButton">Simpan</button> --}}
                    </td>
                </tr>
            </tfoot>   
          </table>
        </div>
    </div>
    </div>
  </div>
</div>
@endsection
@push('scripts')
    <script>
      // click add detail button to add new row
      $('#add_detail').click(function(){
        let no = $('#table_detail tbody tr').length + 1;
        let row = `
          <tr>
            <input type="hidden" name="id[]">
            <td class="border-bottom-0"><h6 class="fw-semibold mb-0">${no}</h6></td>
            <td class="border-bottom-0">
              <select name="produk[]" class="form-control">
                <option value="">Pilih Produk</option>
              </select>
            </td>
            <td class="border-bottom-0">
              <input type="text" name="note[]" class="form-control">
            </td>
            <td class="border-bottom-0">
              <div class="d-flex gap-2 align-items-center justify-content-center">
                <a href="javascript:void(0)" class="btn btn-danger" title="Kurang Qty" onclick="kurang(this)"><i class="ti ti-minus"></i></a>
                <input type="number" name="qty[]" class="form-control text-center" value="0">
                <a href="javascript:void(0)" class="btn btn-primary" title="Tambah Qty" onclick="tambah(this)"><i class="ti ti-plus"></i></a>
              </div>
            </td>
            <td class="border-bottom-0">
              <a href="javascript:void(0)" class="btn btn-danger" title="Hapus Data" onclick="hapus(this)"><i class="ti ti-trash"></i></a>
            </td>
          </tr>
        `;
        $('#table_detail tbody').append(row);
      });

      // click tambah button to increase qty
      function tambah(el){
        let qty = $(el).closest('td').find('input[name="qty[]"]').val();
        qty = parseInt(qty) + 1;
        $(el).closest('td').find('input[name="qty[]"]').val(qty);

        // calculate total
        let harga_satuan = $(el).closest('tr').find('input[name="harga_satuan[]"]').val();
        let total = harga_satuan * qty;
        $(el).closest('tr').find('input[name="total[]"]').val(total);
      }

      // click kurang button to decrease qty
      function kurang(el){
        let qty = $(el).closest('td').find('input[name="qty[]"]').val();
        qty = parseInt(qty) - 1;
        if(qty < 0){
          qty = 0;
        }
        $(el).closest('td').find('input[name="qty[]"]').val(qty);
        
        let harga_satuan = $(el).closest('tr').find('input[name="harga_satuan[]"]').val();
        let total = harga_satuan * qty;
        $(el).closest('tr').find('input[name="total[]"]').val(total);
      }

      // click delete button to remove row
      function hapus(el){
        $(el).closest('tr').remove();
        let no = 1;
        $('#table_detail tbody tr').each(function(){
          $(this).find('td:first-child').text(no);
          no++;
        });
      }

      // calculate total each row when harga_satuan is changed or tambah/kurang qty
      $('#table_detail').on('keyup', 'input[name="harga_satuan[]"], input[name="qty[]"]', function(){
        let harga_satuan = $(this).closest('tr').find('input[name="harga_satuan[]"]').val();
        let qty = $(this).closest('tr').find('input[name="qty[]"]').val();
        let total = harga_satuan * qty;
        $(this).closest('tr').find('input[name="total[]"]').val(total);
      });

      // submit form
      $('#submitButton').click(function(){
        let grand_total = $('#grand_total').val();
        let amount_payment = $('#amount_payment').val();
        let amount_payment_left = $('#amount_payment_left').val();
        if(grand_total == '' || amount_payment == '' || amount_payment_left == ''){
          alert('Grand Total, Amount Payment, dan Amount Payment Left harus diisi');
          return false;
        }
        $('#form').submit();
      });
      
    </script>
@endpush