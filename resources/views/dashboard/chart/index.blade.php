@extends('layouts.master')
@extends('layouts.sidemenu')

@section('title')Chart Dashboaard @endsection

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
                <h4 class="mb-2"> Chart Dashboard</h4>
                </div>
            </div>
        </div>
    </div>
</div>
  



<div class="row">
  <div class="col-md-6 col-xxl-6 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Total Products Sold (Delivered Orders)</h5>
        </div>
      </div>
      <div class="card-body">
        <div id="totalProductSold"></div>
      </div>
    </div>
  </div>

  <div class="col-md-6 col-xxl-6 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Total Income / Expense</h5>
        </div>
      </div>
      <div class="card-body">
        <div id="financialOverviewChart"></div>
      </div>
    </div>
  </div>
</div>


<div class="col-lg-12 col-12 mb-4">
  <div class="card">
    <div class="card-header header-elements">
      <h5 class="card-title mb-0">Last 30 Days Sales</h5>
    </div>
   <div id="last30DaysSales" style="height: 350px;"></div>
  </div>
</div>

<div class="col-lg-12 col-12 mb-4">
  <div class="card">
    <div class="card-header header-elements">
      <h5 class="card-title mb-0">Last 30 Days Income</h5>
    </div>
   <div id="last30DaysIncome" style="height: 350px;"></div>
  </div>
</div>

{{-- <div class="container">
    <h4 class="mb-4"></h4>
    <div id="chart"></div>
</div> --}}

  


@endsection

@section('js')

<script src="{{ asset('assets/vendor/libs/chartjs/chartjs.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
<script>

productData();
fetchLast30DaysSales();
fetchFinancialOverview();
fetchLast30DaysIncome();

//Get Product data
function productData() {
    $.ajax({
        url: '{{ route('dashboard.product.data') }}', // new route
        success: function(result) {
            // result should contain arrays: result.productNames and result.quantities
            renderTotalProductChart(result.productNames, result.quantities);
        },
        error: function(error) {
            console.log(error);
        }
    });
}

//Render Total Product Chart
function renderTotalProductChart(productNames, quantities) {
    const options = {
        chart: {
            type: 'pie',
            height: 350
        },
        labels: productNames,
        series: quantities.map(Number),
        responsive: [{
            breakpoint: 480,
            options: {
                chart: { width: 320 },
                legend: { position: 'bottom' }
            }
        }]
    };

    const chart = new ApexCharts(document.querySelector("#totalProductSold"), options);
    chart.render();
}

//Render last 30 Days Sale
function renderLast30DaysSalesChart(labels, quantities) {
    const options = {
        chart: { type: 'bar', height: 350 },
        series: [{ name: 'Quantity Sold', data: quantities.map(Number) }],
        xaxis: { categories: labels },
        colors: ['#8e4720'],
        dataLabels: { enabled: false },
        responsive: [{
            breakpoint: 480,
            options: { chart: { width: 320 }, legend: { position: 'bottom' } }
        }]
    };

    const chart = new ApexCharts(document.querySelector("#last30DaysSales"), options);
    chart.render();
}

//Last 30 days Sale
function fetchLast30DaysSales() {
    $.ajax({
        url: '{{ route("dashboard.last30days.sales") }}',
        success: function(result) {
            renderLast30DaysSalesChart(result.labels, result.quantities);
        },
        error: function(err) { console.log(err); }
    });
}

//Render Financial Overview
function renderFinancialOverviewChart(labels, series) {
    const options = {
        chart: { type: 'pie', height: 350 },
        labels: labels,
        series: series.map(Number),
        colors: ['#00E396', '#FF4560'], // green = income, red = expense
        responsive: [{
            breakpoint: 480,
            options: { chart: { width: 320 }, legend: { position: 'bottom' } }
        }]
    };

    const chart = new ApexCharts(document.querySelector("#financialOverviewChart"), options);
    chart.render();
}

//Financial Overview
function fetchFinancialOverview() {
    $.ajax({
        url: '{{ route("dashboard.financial.overview") }}',
        success: function(result) {
            renderFinancialOverviewChart(result.labels, result.series);
        },
        error: function(err) { console.log(err); }
    });
}

//Render Last 30 Days income
function renderLast30DaysIncomeChart(labels, series) {
    const options = {
        chart: { type: 'bar', height: 350 },
        series: [{ name: 'Income', data: series.map(Number) }],
        xaxis: { categories: labels },
        colors: ['#f30d0d'],
        dataLabels: { enabled: false },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return '₹' + val.toLocaleString(); // add Rs
                }
            }
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return '₹' + val.toLocaleString();
                }
            }
        },
        responsive: [{
            breakpoint: 480,
            options: { chart: { width: 320 }, legend: { position: 'bottom' } }
        }]
    };

    const chart = new ApexCharts(document.querySelector("#last30DaysIncome"), options);
    chart.render();
}

//Last 30 Days Income
function fetchLast30DaysIncome() {
    $.ajax({
        url: '{{ route("dashboard.last30days.income") }}',
        success: function(result) {
            renderLast30DaysIncomeChart(result.labels, result.series);
        },
        error: function(err) { console.log(err); }
    });
}

</script>

@endsection