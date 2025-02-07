@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Tambah Barang Masuk</h2>
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
                  <option value="">Pilih Supplier</option>
                  @foreach ($suppliers as $item)
                    <option value="{{$item->id}}">{{$item->name}}</option>
                  @endforeach
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

        <form class="table-responsive" id="form" action="{{url('barang-masuk')}}" method="post">
          @csrf
          <table class="table text-nowrap mb-0 align-middle" id="table_detail">
            <thead class="text-dark fs-4">
              <tr>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">No</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Produk</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Harga Satuan</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Note</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">QTY</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Total</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Action</h6>
                </th>
              </tr>
            </thead>
            <tbody> 
              <tfoot>
                <tr>
                  <td colspan="5" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Grand Total</h6></td>
                  <td class="border-bottom-0 p-0"><input type="number" class="form-control border-0 text-end" id="grand_total" name="grand_total" readonly></td>
                  <td class=" p-0 border-bottom-0"></td>
                </tr>  
                {{-- payment method --}}
                <tr>
                  <td colspan="5" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Metode Pembayaran</h6></td>
                  <td class="border-bottom-0 p-0">
                    <select name="payment_method" class="form-control border-1">
                      <option value="">Pilih Metode Pembayaran</option>
                      <option value="cash">Cash</option>
                      <option value="transfer">Transfer</option>
                    </select>
                  </td>
                  <td class=" p-0 border-bottom-0"></td>
                {{-- Amount Payment --}}
                <tr>
                  <td colspan="5" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Bayar</h6></td>
                  <td class="border-bottom-0 p-0"><input type="number" class="form-control border-1 text-end" id="amount_payment" name="amount_payment"></td>
                  <td class=" p-0 border-bottom-0"></td>
                </tr>
                {{-- amount payment left --}}
                <tr>
                  <td colspan="5" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Sisa</h6></td>
                  <td class="border-bottom-0 p-0"><input type="number" class="form-control border-0 text-end" id="amount_payment_left" name="amount_payment_left" readonly></td>
                  <td class=" p-0 border-bottom-0"></td>
                </tr>
                <tr>
                  <td colspan="5" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Keterangan</h6></td>
                  <td class="border-bottom-0 p-0"><textarea name="keterangan" class="form-control border-1 text-end"></textarea></td>
                  <td class=" p-0 border-bottom-0"></td>
                </tr>
                {{-- save --}}
                <tr>
                  <td colspan="7" class="text-end border-bottom-0 p-0">
                    <button type="submit" class="btn btn-primary" id="submitButton">Simpan</button>
                  </td>
                </tr>
              </tfoot>          
            </tbody>
          </table>
        </form>
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
            <td class="border-bottom-0"><h6 class="fw-semibold mb-0">${no}</h6></td>
            <td class="border-bottom-0">
              <select name="produk[]" class="form-control">
                <option value="">Pilih Produk</option>
                @foreach ($products as $item)
                  <option value="{{$item->id}}">{{$item->name}}</option>
                @endforeach
              </select>
            </td>
            <td class="border-bottom-0">
              <input type="number" name="harga_satuan[]" class="form-control text-end">
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
              <input type="number" name="total[]" class="form-control text-end" readonly>
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

        // recalculating grand total
        calculateGrandTotal();
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

        // recalculating grand total
        calculateGrandTotal();
      }

      // click delete button to remove row
      function hapus(el){
        $(el).closest('tr').remove();
        let no = 1;
        $('#table_detail tbody tr').each(function(){
          $(this).find('td:first-child').text(no);
          no++;
        });
        
        // recalculating grand total
        calculateGrandTotal();
      }

      // calculate total each row when harga_satuan is changed or tambah/kurang qty
      $('#table_detail').on('keyup', 'input[name="harga_satuan[]"], input[name="qty[]"]', function(){
        let harga_satuan = $(this).closest('tr').find('input[name="harga_satuan[]"]').val();
        let qty = $(this).closest('tr').find('input[name="qty[]"]').val();
        let total = harga_satuan * qty;
        $(this).closest('tr').find('input[name="total[]"]').val(total);

        // recalculating grand total
        calculateGrandTotal();
      });

      // recalculating grand total as a function 
      function calculateGrandTotal(){
        let grand_total = 0;
        $('#table_detail tbody tr').each(function(){
          // only calculate total if qty and harga_satuan is not empty
          if($(this).find('input[name="qty[]"]').val() != '' && $(this).find('input[name="harga_satuan[]"]').val() != ''){
            grand_total += parseInt($(this).find('input[name="total[]"]').val());
          }
        });
        $('#grand_total').val(grand_total);
      }

      // calculate amount payment left
      $('#table_detail').on('keyup', 'input[name="amount_payment"]', function(){
        let grand_total = $('#grand_total').val();
        let amount_payment = $(this).val();
        let amount_payment_left = grand_total - amount_payment;
        $('#amount_payment_left').val(amount_payment_left);
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