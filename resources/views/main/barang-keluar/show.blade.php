@extends('layouts.app')

@section('content')
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4" id="form" >
          @csrf
        <div class="d-flex justify-content-between align-items-center">
          <h2 class="fw-semibold mb-4">Edit Barang Keluar</h2>
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
                <label class="form-label" for="tgl_keluar">Tgl Keluar</label>
                <input type="date" class="form-control" id="tgl_keluar" name="tgl_keluar" value="{{ date('Y-m-d', strtotime($barang_keluar->outbound_date)) }}" readonly>
              </div>
            </div>
            <div class="col-lg-4">
              {{-- @dd($barang_keluar->customer_name) --}}
              <div class="form-group">
                <label class="form-label" for="supplier">Customer</label>
                <input type="text" class="form-control" id="customer" name="customer" value="{{ $barang_keluar->customer_name }}"readonly>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="form-group">
                <label class="form-label" for="petugas">Petugas</label>
                <input type="text" class="form-control" id="petugas" name="petugas" value="{{ $barang_keluar->user->name }}" disabled>
              </div>
            </div>
          </div>
        </div>  
        
        <hr class="my-4">

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
              @foreach ($barang_keluar->details as $item)
                <tr>
                  <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                  <td class="border-bottom-0">
                    <select name="produk[]" class="form-control" disabled>
                      <option value="">Pilih Produk</option>
                      @foreach ($products as $product)
                        @if ($product->id == $item->product_id)
                          <option value="{{$product->id}}" selected>{{$product->name}}</option>
                        @endif
                        <option value="{{$product->id}}">{{$product->name}}</option>
                      @endforeach
                    </select>
                  </td>
                  <td class="border-bottom-0">
                    <input type="number" name="harga_satuan[]" class="form-control" readonly value="{{$item->product->sell_price}}"> 
                  </td>
                  <td class="border-bottom-0">
                    <input type="text" name="note[]" class="form-control" value="{{$item->note}}" readonly>
                  </td>
                  <td class="border-bottom-0">
                    <div class="d-flex gap-2 align-items-center justify-content-center">
                      {{-- <a href="javascript:void(0)" class="btn btn-danger" title="Kurang Qty" onclick="kurang(this)"><i class="ti ti-minus"></i></a> --}}
                      <input type="number" name="qty[]" class="form-control text-center" value="{{$item->quantity}}" max="{{$item->product->stock > $item->quantity ? $item->product->stock : $item->quantity}}" readonly>
                      {{-- <a href="javascript:void(0)" class="btn btn-primary" title="Tambah Qty" onclick="tambah(this)"><i class="ti ti-plus"></i></a> --}}
                    </div>
                  </td>
                  <td class="border-bottom-0">
                    <input type="number" name="total[]" class="form-control" value="{{$item->price_per_unit ? ($item->price_per_unit * $item->quantity) : ($item->product->sell_price * $item->quantity)}}" readonly>
                  </td>
                  <td class="border-bottom-0">
                    {{-- <a href="javascript:void(0)" class="btn btn-danger" title="Hapus Data" onclick="hapus(this)"><i class="ti ti-trash"></i></a> --}}
                  </td>
                </tr>
              @endforeach
            </tbody>
            <tfoot class=" pt-5">
              {{-- toggle include shipping --}}
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Shipping</h6></td>
                <td colspan="" class="border-bottom-0 p-0">
                  <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" @if ($barang_keluar->shipping_address != null) checked @endif disabled>
                        
                    <label class="btn btn-outline-primary" for="btnradio1">Diambil</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off"  @if (!$barang_keluar->shipping_address) checked @endif disabled>
                    <label class="btn btn-outline-primary" for="btnradio2">Diantar</label>
                  </div>
                </td>
              </tr>
              <tr class="d-none" id="shipping_address_row">
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Alamat</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><textarea name="shipping_address" class="form-control border-1" readonly>{{$barang_keluar->shipping_address ?? ""}}</textarea></td>
              </tr>
              <tr class="d-none" id="shipping_fee_row">
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Biaya Kirim</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="shipping_fee" class="form-control fw-bold" name="shipping_fee" value="{{$barang_keluar->shipping_fee ?? ""}}" readonly></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Total</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="all_total" class="form-control border-0 fw-bold" name="all_total" value="{{$barang_keluar->total_payment + $barang_keluar->discount}}" readonly></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Discount</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="discount" class="form-control" name="discount" value="{{$barang_keluar->discount}}" readonly></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Grand Total</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><input type="number" id="grand_total" class="form-control border-0 fw-bold" name="total_payment" readonly value="{{$barang_keluar->total_payment ?? ""}}" readonly></td>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Metode Pembayaran</h6></td>
                <td colspan="" class="border-bottom-0 p-0">
                  <select name="payment_method" class="form-control" disabled>
                    <option value="">Pilih Metode Pembayaran</option>
                    <option value="cash" @if ($barang_keluar->payment_method == 'cash') selected @endif>Cash</option>
                    <option value="transfer" @if ($barang_keluar->payment_method == 'transfer') selected @endif>Transfer</option>
                  </select>
              </tr>
              <tr>
                <td colspan="4"></td>
                <td colspan="" class="text-start border-bottom-0 p-0"><h6 class="fw-semibold mb-0">Keterangan</h6></td>
                <td colspan="" class="border-bottom-0 p-0"><textarea name="keterangan" class="form-control border-1" readonly>{{$barang_keluar->keterangan}}</textarea></td>
              </tr>
              {{-- save --}}
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
                <input type="number" name="qty[]" class="form-control text-center" value="0" max="">
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
      function tambah(el) {
        let qty_input = $(el).closest('td').find('input[name="qty[]"]');
        let max_qty = parseInt(qty_input.attr('max')) || Infinity; // Get max attribute, default to Infinity if not set
        let qty = parseInt(qty_input.val()) || 0;

        if (qty < max_qty) {
            qty += 1;
            qty_input.val(qty);
        }

        // Calculate total
        updateTotal(el, qty);
      }

      // Click kurang button to decrease qty
      function kurang(el) {
          let qty_input = $(el).closest('td').find('input[name="qty[]"]');
          let qty = parseInt(qty_input.val()) || 0;

          // 

          if (qty > 0) {
              qty -= 1;
              qty_input.val(qty);
          }

          // Calculate total
          updateTotal(el, qty);
      }
      // Function to calculate and update total
      function updateTotal(el, qty) {
          let harga_satuan = $(el).closest('tr').find('input[name="harga_satuan[]"]').val();
          let total = harga_satuan * qty;
          $(el).closest('tr').find('input[name="total[]"]').val(total);

          calculateTotal();
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

        // add shipping fee
        let shipping_fee = $('#shipping_fee').val();
        all_total += parseInt(shipping_fee);
        $('#all_total').val(all_total);

        let discount = $('#discount').val();
        let grand_total = all_total - discount;
        $('#grand_total').val(grand_total);
      }

      // When produk is changed, set harga_satuan from /api/product/{id}
      $('#table_detail').on('change', 'select[name="produk[]"]', function(e) {
          let id = $(this).val();
          let currentSelect = $(this);
          let selected = false;

          $('#table_detail select[name="produk[]"]').each(function() {
              if ($(this).val() == id && !$(this).is(currentSelect)) {
                  selected = true;
              }
          });

          if (selected) {
              alert('Produk sudah dipilih sebelumnya!');
              // Remove selected option
              $(this).val('');
              return;
          }

          if (selected) {
          }
          let harga_satuan = $(this).closest('tr').find('input[name="harga_satuan[]"]');
          let qty_input = $(this).closest('tr').find('input[name="qty[]"]');

          $.get(`/api/product/${id}`, function(data) {
              harga_satuan.val(data.sell_price);

              // Set max qty and apply to buttons
              if (data.stock !== null && !isNaN(data.stock)) {
                  qty_input.attr('max', data.stock);
              } else {
                  qty_input.removeAttr('max'); // Remove max if stock is invalid
              }
          });
      });

      // calculate total when discount is changed
      $('#discount').keyup(function(){
        calculateTotal();
      });
      
      // event btnradio2 to toggle shipping_address_row and shipping_fee_row
      $('#btnradio2').click(function(){
        // toggle shipping_address_row
        $('#shipping_address_row').removeClass('d-none');
        // toggle shipping_fee_row
        $('#shipping_fee_row').removeClass('d-none');
        // calculate total
        calculateTotal();
      });

      // event btnradio1 to toggle shipping_address_row and shipping_fee_row
      $('#btnradio1').click(function(){
        // toggle shipping_address_row
        $('#shipping_address_row').addClass('d-none');
        // toggle shipping_fee_row
        $('#shipping_fee_row').addClass('d-none');
        // calculate total
        calculateTotal();
      });

      // calculate amount_payment_left when amount_payment is changed
      $('#amount_payment').keyup(function(){
        let grand_total = $('#grand_total').val();
        let amount_payment = $(this).val();
        let amount_payment_left = grand_total - amount_payment;
        $('#amount_payment_left').val(amount_payment_left);
      });

      // calculate total and grand total when shipping_fee is changed
      $('#shipping_fee').keyup(function(){
        calculateTotal();
      });
    </script>
@endpush