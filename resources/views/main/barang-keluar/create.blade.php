@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <form class="card-body p-4" id="form" action="{{url('barang-keluar')}}" method="post">
          @csrf
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Tambah Barang Keluar</h2>
        </div>
        <div class="container-fluid mb-3"> 
          <div class="row">
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="tgl_keluar">Tgl Keluar</label>
                <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar">
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="supplier">Customer</label>
                <input type="text" class="form-control" id="customer" name="customer">
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
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Harga Satuan</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Note</h6>
                </th>
                <th class="border-bottom-0" width="15%">
                  <h6 class="fw-semibold mb-0">QTY</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Total</h6>
                </th>
                <th class="border-bottom-0" width="5%">
                  <h6 class="fw-semibold mb-0">Action</h6>
                </th>
              </tr>
            </thead>
            <tbody>     
            </tbody>
            <tfoot class="d-none pt-5">
              {{-- toggle include shipping --}}
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Shipping</h6></td>
                <td colspan="" class="border-bottom-0 p-0">
                  <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="btnradio1">Diambil</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio2">Diantar</label>
                  </div>
                </td>
              </tr>
              <tr class="d-none" id="shipping_address_row">
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Alamat</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><textarea name="shipping_address" class="form-control border-1"></textarea></td>
              </tr>
              <tr class="d-none" id="shipping_fee_row">
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Biaya Kirim</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="shipping_fee" class="form-control fw-bold" name="shipping_fee"></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Total</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="all_total" class="form-control border-0 fw-bold" name="all_total" readonly></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Discount</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="discount" class="form-control" name="discount"></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Grand Total</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="grand_total" class="form-control border-0 fw-bold" name="grand_total" readonly></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Amount Payment</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="amount_payment" class="form-control" name="amount_payment"></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Amount Payment Left</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="amount_payment_left" class="form-control" name="amount_payment_left"></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Keterangan</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><textarea name="keterangan" class="form-control border-1"></textarea></td>
              </tr>
              {{-- save --}}
              <tr>
                <td colspan="7" class="text-end border-bottom-0 p-2">
                  <button type="button" class="btn btn-primary" id="submitButton">Simpan</button>
                </td>
              </tr>
            </tfoot>      
          </table>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
@push('scripts')
    <script>
      // click add detail button to add new row
      $('#add_detail').click(function(){
        // closest tfooter to show
        $('#table_detail tfoot').removeClass('d-none');
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
              <input type="number" name="harga_satuan[]" class="form-control" readonly>
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
              <input type="number" name="total[]" class="form-control">
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

        calculateTotal()
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
        
        calculateTotal()
      }

      // click delete button to remove row
      function hapus(el){
        $(el).closest('tr').remove();
        let no = 1;
        $('#table_detail tbody tr').each(function(){
          $(this).find('td:first-child').text(no);
          no++;
        });
        
        calculateTotal()
      }

      // calculate total each row when harga_satuan is changed or tambah/kurang qty
      $('#table_detail').on('keyup', 'input[name="harga_satuan[]"], input[name="qty[]"]', function(){
        let harga_satuan = $(this).closest('tr').find('input[name="harga_satuan[]"]').val();
        let qty = $(this).closest('tr').find('input[name="qty[]"]').val();
        let total = harga_satuan * qty;
        $(this).closest('tr').find('input[name="total[]"]').val(total);
        
        calculateTotal()
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

      // calculate all_total and grand_total as a function
      function calculateTotal(){
        let all_total = 0;
        $('#table_detail tbody tr').each(function(){
          let total = $(this).find('input[name="total[]"]').val();
          // calculate if total is not empty
          if(total == ''){
            total = 0;
          }
          all_total += parseInt(total);
        });
        $('#all_total').val(all_total);

        let discount = $('#discount').val();
        let grand_total = all_total - discount;
        $('#grand_total').val(grand_total);
      }

      // wheen produk is changed, set harga_satuan get from /api/product/{id}
      $('#table_detail').on('change', 'select[name="produk[]"]', function(){
        let id = $(this).val();
        let harga_satuan = $(this).closest('tr').find('input[name="harga_satuan[]"]');
        $.get(`/api/product/${id}`, function(data){
          console.log(data);
          
          harga_satuan.val(data.sell_price);
        });
      });

      // calculate total when discount is changed
      $('#discount').keyup(function(){
        calculateTotal();
      });
      
      // event btnradio2 to toggle shipping_address_row and shipping_fee_row
      $('#btnradio2').click(function(){
        // toggle shipping_address_row
        $('#shipping_address_row').toggleClass('d-none');
        // toggle shipping_fee_row
        $('#shipping_fee_row').toggleClass('d-none');
        // calculate total
        calculateTotal();
      });
    </script>
@endpush