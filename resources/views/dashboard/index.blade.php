@extends('layouts.master')
@extends('layouts.sidemenu')

@section('title')Dashboaard @endsection

@section('css')
<style>


  @media only screen and (min-width: 1030px) and (max-width: 1369px) {
      .myChartDiv {
          display: flex;
          justify-content: center;
          align-items: center;
          width: 400px;
          height: 240px;
      }
  }

  @media only screen and (min-width: 1370px) and (max-width: 1605px) {
      .myChartDiv {
          display: flex;
          justify-content: center;
          align-items: center;
          width: 515px;
          height: 360px;
      }
      .chartjs {
      height: 200px; /* Adjust the height as needed */
    }
  }

  /* Large screens ----------- */
  @media only screen and (min-width : 1824px) {
      .myChartDiv {
          display: flex;
          justify-content: center;
          align-items: center;
          width: 754px;
          height: 384px;
      }

    .chartjs {
      height: 200px; /* Adjust the height as needed */
    }
  }
</style>
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}">
{{-- <link rel="stylesheet" href="" /> --}}

@endsection
@section('content')

<div class="row gy-4 mb-4">
  <div class="col-lg-12">
    <div class="card h-100">
      <div class="card-header">
        <div class="d-flex justify-content-between">
          <h4 class="mb-2"> Dashboard</h4>
        </div>
    </div>
  </div>
</div>
{{-- <div class="row gy-4 mb-4">
  <div class="col-sm-6 col-lg-3 mb-2">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-clipboard-text-clock mdi-36px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{$interactions['tdy']}}</h4>
        </div>
        <h5 class="mb-0 text-heading">Total Calls (TDY)</h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-2">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="mdi mdi-calendar-badge mdi-36px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{$interactions['ystdy']}}</h4>
        </div>
        <h5 class="mb-0 text-heading">Total Calls (YSTDY)</h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-2">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
         <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-success">
              <i class="mdi mdi-calendar-range mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{$interactions['mtd']}}</h4>
        </div>
        <h5 class="mb-0 text-heading">Total Calls (MTD)</h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-2">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-calendar-expand-horizontal-outline mdi-36px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{$interactions['lmtd']}}</h4>
        </div>
        <h5 class="mb-0 text-heading">Total Calls (LMTD)</h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-2">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info"><i class="mdi mdi-alpha-c-box mdi-36px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{$interactions['complaint']}}</h4>
        </div>
        <h5 class="mb-0 text-heading">Complaints (MTD)</h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-2">
    <div class="card card-border-shadow-warning  h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning ">
              <i class="mdi mdi-alpha-e-box mdi-36px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{$interactions['enquiry']}}</h4>
        </div>
        <h5 class="mb-0 text-heading">Enquiry (MTD)</h5>
      </div>
    </div>
  </div>
  <div class="col-sm-6 col-lg-3 mb-2">
    <div class="card card-border-shadow-secondary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
         <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-secondary">
              <i class="mdi mdi-alpha-r-box mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{$interactions['request']}}</h4>
        </div>
        <h5 class="mb-0 text-heading">Request (MTD)</h5>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-3 mb-2">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger"><i class="mdi mdi-alpha-o-box mdi-36px"></i></span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{$interactions['others']}}</h4>
        </div>
        <h5 class="mb-0 text-heading">Others (MTD)</h5>
      </div>
    </div>
  </div>
</div> --}}

  {{-- <div class="col-sm-6 col-lg-12 mb-2">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
         <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="mdi mdi-alpha-c-box mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">
            {{$interactions['call_transfer']}}
          </h4>
        </div>
        <h5 class="mb-0 text-heading">Call Transfer (MTD)</h5>
      </div>
    </div>
  </div>
   --}}
  
  <div class="row gy-4 mb-4">

    <div class="col-12">
      <h4 class="fw-bold mb-3">Expenses Overview</h4>
    </div>


  {{-- Expenses --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="mdi mdi-cash mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $expenses['total'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Total Expenses</h5>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="mdi mdi-calendar mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $expenses['month'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">This Month Expenses</h5>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="mdi mdi-calendar-week mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $expenses['week'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">This Week Expenses</h5>
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-success">
              <i class="mdi mdi-calendar-today mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $expenses['today'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Today Expenses</h5>
      </div>
    </div>
  </div>

   <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-success">
              <i class="mdi mdi-calendar-remove mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $expenses['yesterday'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Yesterday Expenses</h5>
      </div>
    </div>
  </div>

  


    <div class="col-12">
      <h4 class="fw-bold mb-3">Sales Quantity Overview</h4>
    </div>


  {{-- Total Quantity Sold --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="mdi mdi-package-variant-closed mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $sales['totalQty'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Total Qty Sold</h5>
      </div>
    </div>
  </div>

  {{-- This Month --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-success">
              <i class="mdi mdi-calendar-month mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $sales['monthQty'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">This Month Qty Sold</h5>
      </div>
    </div>
  </div>

  {{-- This Week --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="mdi mdi-calendar-week mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $sales['weekQty'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">This Week Qty Sold</h5>
      </div>
    </div>
  </div>

  {{-- Today --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info">
              <i class="mdi mdi-calendar-today mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $sales['todayQty'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Today Qty Sold</h5>
      </div>
    </div>
  </div>

  {{-- Yesterday --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="mdi mdi-calendar-remove mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $sales['yesterdayQty'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Yesterday Qty Sold</h5>
      </div>
    </div>
  </div>

    <div class="col-12">
      <h4 class="fw-bold mb-3">Earnings Overview</h4>
    </div>

  {{-- Total Earnings --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-success">
              <i class="mdi mdi-currency-inr mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $earnings['total'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Total Earnings</h5>
      </div>
    </div>
  </div>

  {{-- This Month --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-primary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-primary">
              <i class="mdi mdi-calendar-month mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $earnings['month'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">This Month Earnings</h5>
      </div>
    </div>
  </div>

  {{-- This Week --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="mdi mdi-calendar-week mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $earnings['week'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">This Week Earnings</h5>
      </div>
    </div>
  </div>

  {{-- Today --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info">
              <i class="mdi mdi-calendar-today mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $earnings['today'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Today Earnings</h5>
      </div>
    </div>
  </div>

  {{-- Yesterday --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="mdi mdi-calendar-remove mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $earnings['yesterday'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Yesterday Earnings</h5>
      </div>
    </div>
  </div>

  


   <div class="col-12">
      <h4 class="fw-bold mb-3">Delivery Overview</h4>
    </div>


  {{-- Pending --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-warning h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-warning">
              <i class="mdi mdi-truck mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $deliveryStatus['pending'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Pending</h5>
      </div>
    </div>
  </div>

  {{-- Shipped --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-info">
              <i class="mdi mdi-truck-fast mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $deliveryStatus['shipped'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Shipped</h5>
      </div>
    </div>
  </div>

  {{-- Delivered --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-success h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-success">
              <i class="mdi mdi-check-circle mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $deliveryStatus['delivered'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Delivered</h5>
      </div>
    </div>
  </div>

  {{-- Cancelled --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-danger h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-danger">
              <i class="mdi mdi-cancel mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $deliveryStatus['cancelled'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Cancelled</h5>
      </div>
    </div>
  </div>

  {{-- Pending Amounts --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-secondary h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded bg-label-secondary">
              <i class="mdi mdi-cash-remove mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $earnings['pending'] }}</h4>
        </div>
        <h5 class="mb-0 text-heading">Pending Amounts</h5>
      </div>
    </div>
  </div>

  {{-- You can repeat this block for Sales (quantity sold), Earnings, Pending, Delivery Status, Profit/Loss --}}
  {{-- Example for Profit / Loss --}}
  <div class="col-sm-6 col-lg-2 mb-2">
    <div class="card card-border-shadow-info h-100">
      <div class="card-body">
        <div class="d-flex align-items-center mb-2 pb-1">
          <div class="avatar me-2">
            <span class="avatar-initial rounded {{ $profitOrLoss >= 0 ? 'bg-label-success' : 'bg-label-danger' }}">
              <i class="mdi {{ $profitOrLoss >= 0 ? 'mdi-trending-up' : 'mdi-trending-down' }} mdi-36px"></i>
            </span>
          </div>
          <h4 class="ms-1 mb-0 display-6">{{ $profitOrLoss }}</h4>
        </div>
        <h5 class="mb-0 text-heading">{{ $profitOrLoss >= 0 ? 'Profit' : 'Loss' }}</h5>
      </div>
    </div>
  </div>
  

</div>
  


@endsection

@section('js')

<script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>


</script>

@endsection