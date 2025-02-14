@extends('layouts.app')

@section('content')
    
<div class="row">
  <div class="col-lg-8 d-flex align-items-strech">
    <div class="card w-100">
      <div class="card-body">
        <div class="d-sm-flex d-block align-items-center justify-content-between mb-9">
          <div class="mb-3 mb-sm-0">
            <h5 class="card-title fw-semibold">Rekap Barang Masuk dan Keluar</h5>
          </div>
        </div>
        <div id="chart"></div>
      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="row">
      <div class="col-lg-12">
        <!-- Yearly Breakup -->
        <div class="card overflow-hidden">
          <div class="card-body p-4">
            <h5 class="card-title mb-9 fw-semibold">Pendapatan Tahun Ini</h5>
            <div class="row align-items-center">
              <div class="col-12">
                <h4 class="fw-semibold mb-3">Rp {{$yearEarnings}}</h4>
                <div class="d-flex align-items-center mb-3">
                  <span
                    class="me-1 rounded-circle bg-light-success round-20 d-flex align-items-center justify-content-center">
                    @if ($yearEarningsPercentage >= 0)
                    <i class="ti ti-arrow-up-left text-success"></i>
                    @else 
                    <i class="ti ti-arrow-down-right text-danger"></i>
                    @endif
                  </span>
                  <p class="text-dark me-1 fs-3 mb-0">{{$yearEarningsPercentage}}%</p>
                  <p class="fs-3 mb-0">last year</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <!-- Monthly Earnings -->
        <div class="card">
          <div class="card-body">
            <div class="row alig n-items-start">
              <div class="col-12">
                <h5 class="card-title mb-9 fw-semibold"> Pengeluaran Bulan Ini </h5>
                <h4 class="fw-semibold mb-3">Rp {{$yearExpenses}}</h4>
                <div class="d-flex align-items-center pb-1">
                  <span
                    class="me-2 rounded-circle bg-light-danger round-20 d-flex align-items-center justify-content-center">
                    @if ($yearExpensesPercentage >= 0)
                    <i class="ti ti-arrow-up-left text-success"></i>
                    @else 
                    <i class="ti ti-arrow-down-right text-danger"></i>
                    @endif
                  </span>
                  <p class="text-dark me-1 fs-3 mb-0">{{$yearExpensesPercentage}}%</p>
                  <p class="fs-3 mb-0">last year</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12 d-flex align-items-stretch">
    <div class="card w-100">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Recent Transactions</h5>
        <div class="table-responsive">
          <table class="table text-nowrap mb-0 align-middle">
            <thead class="text-dark fs-4">
              <tr>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">No</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Tanggal</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Petugas</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Barang</h6>
                </th>
                <th class="border-bottom-0">
                  <h6 class="fw-semibold mb-0">Total</h6>
                </th>
              </tr>
            </thead>
            <tbody>
              @if ($recentTransaction->isEmpty())
                  <tr><td class="text-center" colspan="5">Belum Ada Data Transaksi </td></tr>
              @endif
              @foreach ($recentTransaction as $item)
              <tr>
                <td class="border-bottom-0"><h6 class="fw-semibold mb-0">{{$loop->iteration}}</h6></td>
                <td class="border-bottom-0">
                  {{$item->outbound_date}}
                </td>
                <td class="border-bottom-0">
                  <p class="mb-0 fw-normal">
                  {{$item->user->name}}</p>
                </td>
                <td class="border-bottom-0">
                  <div class="d-flex align-items-center gap-2">
                    <ul>
                      @foreach ($item->outboundDetails as $detail)
                      <li class="mb-0">{{$detail->product->name}} x {{$detail->qty}}</li>
                      @endforeach
                    </ul>
                  </div>
                </td>
                <td class="border-bottom-0">
                  <h6 class="fw-semibold mb-0 fs-4">{{$item->total_payment}}</h6>
                </td>
              </tr>       
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-lg-12">
    <div class="card w-100">
      <div class="card-body p-4">
        <h5 class="card-title fw-semibold mb-4">Top Product Sales</h5>
        <div class="container-fluid">
          <div class="row">
            @if ($top6ProductOutbound->isEmpty())
                <div class="col-lg-12">
                  <div class="alert alert-warning" role="alert">
                    Belum ada data penjualan
                  </div>
                </div>
            @endif
            @foreach ($top6ProductOutbound as $item)
            <div class="col-sm-3 col-xl-2">
              <div class="card overflow-hidden rounded-2">
                <div class="position-relative">
                  <a href="javascript:void(0)"><img src="{{ asset('storage/' . $item->image) }}" class="card-img-top rounded-0" alt="..."></a>
                </div>
                <div class="card-body pt-3 p-4">
                  <h6 class="fw-semibold fs-4">{{$item->name}}</h6>
                  <div class="d-flex align-items-center justify-content-between">
                    <h6 class="fw-semibold fs-4 mb-0">Total Penjualan : {{$item->total_quantity}}</h6>
                  </div>
                </div>
              </div>
            </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
    <script>
      $(function () {


  // =====================================
  // Profit
  // =====================================

  let dataEarningExpenses = [];
  let xaxisCategories = [];
  let chart = null;
  // get data series from ajax request url (/api/earnings-expenses)
  $.get('{{url('/api/earnings-expenses')}}', function (data) {
    dataEarningExpenses = [
      { name: "Earnings this month:", data: data.earnings },
      { name: "Expense this month:", data: data.expenses },
    ];
    xaxisCategories = data.months;

    console.log(dataEarningExpenses);
    console.log(xaxisCategories);
    console.log(data);
    
    
    
    chart = {
      // series: [
      //   { name: "Earnings this month:", data: [355, 390, 300, 350, 390, 180, 355, 390] },
      //   { name: "Expense this month:", data: [280, 250, 325, 215, 250, 310, 280, 250] },
      // ],

      series: dataEarningExpenses,

      chart: {
        type: "bar",
        height: 345,
        offsetX: -15,
        toolbar: { show: true },
        foreColor: "#adb0bb",
        fontFamily: 'inherit',
        sparkline: { enabled: false },
      },


      colors: ["#5D87FF", "#49BEFF"],


      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: "35%",
          borderRadius: [6],
          borderRadiusApplication: 'end',
          borderRadiusWhenStacked: 'all'
        },
      },
      markers: { size: 0 },

      dataLabels: {
        enabled: false,
      },


      legend: {
        show: false,
      },


      grid: {
        borderColor: "rgba(0,0,0,0.1)",
        strokeDashArray: 3,
        xaxis: {
          lines: {
            show: false,
          },
        },
      },

      xaxis: {
        type: "category",
        categories: xaxisCategories,
        labels: {
          style: { cssClass: "grey--text lighten-2--text fill-color" },
        },
      },


      yaxis: {
        show: true,
        min: 0,
        max: 400,
        tickAmount: 4,
        labels: {
          style: {
            cssClass: "grey--text lighten-2--text fill-color",
          },
        },
      },
      stroke: {
        show: true,
        width: 3,
        lineCap: "butt",
        colors: ["transparent"],
      },


      tooltip: { theme: "light" },

      responsive: [
        {
          breakpoint: 600,
          options: {
            plotOptions: {
              bar: {
                borderRadius: 3,
              }
            },
          }
        }
      ]


    };

    chart = new ApexCharts(document.querySelector("#chart"), chart);
    chart.render();
  });


})
    </script>
@endpush