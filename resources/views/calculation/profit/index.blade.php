@extends('layouts.master')
@extends('layouts.sidemenu')

@section('title') Profit Calculator @endsection
@section('css')
<style>
    .select2-container {
        z-index: 100000;
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}">

@endsection

@section('content')


<div class="card">
  <div class="card-datatable table-responsive pt-0">
    <div class="card-header flex-column flex-md-row pb-0">
      <div class="head-label">
        <h4 class="card-header">Profit Calculator</h4>
      </div>
    </div>

    <div class="card-body pt-2 mt-0">
      <form id="settingsForm" onsubmit="event.preventDefault(); calculateProfit();">
        @csrf
        <div class="row gy-4">
          <div class="col-md-4">
            <div class="form-floating form-floating-outline">
              <input class="form-control" type="number" step="0.01" id="ingredients_expense"
                     name="ingredients_expense" placeholder="Total Expense" required />
              <label for="ingredients_expense">Ingredients Expense (₹)</label>
            </div><hr>
          </div>

          <div class="col-md-4">
            <div class="form-floating form-floating-outline">
              <input class="form-control" type="number" step="0.01" id="sticker_charge"
                     name="sticker_charge" placeholder="Sticker Charge" required />
              <label for="sticker_charge">Sticker Charge (Per Bottle ₹)</label>
            </div><hr>
          </div>

          <div class="col-md-4">
            <div class="form-floating form-floating-outline">
              <input class="form-control" type="number" step="0.01" id="price_per_bottle"
                     name="price_per_bottle" placeholder="Price Per Bottle" required />
              <label for="price_per_bottle">Selling Price Per Bottle (₹)</label>
            </div><hr>
          </div>
        </div>

        <div class="row gy-4">
          <div class="col-md-4">
            <div class="form-floating form-floating-outline">
              <input class="form-control" type="number" step="0.01" id="total_quantity"
                     name="total_quantity" placeholder="Pickle Total Qty (gm)" required />
              <label for="total_quantity">Pickle Total Qty (gm)</label>
            </div><hr>
          </div>

          <div class="col-md-4">
            <div class="form-floating form-floating-outline">
              <input class="form-control" type="number" step="0.01" id="bottle_volume"
                     name="bottle_volume" placeholder="Bottle Volume (gm)" required />
              <label for="bottle_volume">Bottle Volume (gm)</label>
            </div><hr>
          </div>

          <div class="col-md-4">
            <div class="form-floating form-floating-outline">
              <input class="form-control" type="number" step="0.01" id="bottle_cost"
                     name="bottle_cost" placeholder="Bottle Cost" required />
              <label for="bottle_cost">Bottle Cost (Per Bottle ₹)</label>
            </div><hr>
          </div>

         
        </div>
         <div class="row gy-4">
            <div class="col-md-4">
             <div class="form-floating form-floating-outline">
              <input class="form-control" type="number" step="0.01" id="travel_cost"
                     name="travel_cost" placeholder="Travel Cost" required />
              <label for="travel_cost">Travel Cost (₹)</label>
            </div><hr>
            </div>
         </div>

        <div class="mt-4">
          <button type="submit" class="btn btn-primary me-2">Calculate</button>
        </div>
      </form>

      <!-- Result table will appear here -->
      <div id="resultTable" class="mt-4"></div>
    </div>
  </div>
</div>


<div class="row">

</div>

@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

<script type="text/javascript">
    $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
      
            $(document).on('click', 'a[data-async="true"]', function (e) {
                e.preventDefault();
                var self    = $(this),
                    url     = self.data('endpoint'),
                    target  = self.data('target'),
                    cache   = self.data('cache'),
                    act_type = self.data('act_type');
                if(act_type=='revokeAccess'){
                    Swal.fire({
                        title: 'Are you sure?',
                        icon: 'warning',
                        confirmButton: 'btn btn-success',
                        cancelButton: 'btn btn-danger',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, proceed!',
                        cancelButtonText: 'No, cancel!',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                           $.ajax({
                                url  : url,
                                type : "POST",
                                cache : cache,
                            }).done(function(data) {
                               
                                if (data.status == 'success') {

                                    Swal.fire({
                                    icon: 'success',
                                    text: data.message,
                                    type: 'success',
                                    customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                    },
                                    buttonsStyling: false,
                                    timer: 3000,
                                    }).then((result) => {
                                    location.reload();
                                    })
                                }
                            }).fail(function(data) {
                                console.log(error);
                            }); 
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                           
                        }

                    });   
                }
                else{

                    $.ajax({
                        url     : url,
                        cache   : cache,
                        success : function(result){
                            console.log(result);
                            if (target !== 'undefined'){
                                $('#erralert').html("");
                                $('#erralert').hide();
                            }
                        },
                        error : function(error){
                            console.log(error);
                        },
                    });
                }
            });
           
        });

</script>


<script type="text/javascript">

function calculateProfit() {
    // Grab input values
    const ingredients = parseFloat(document.getElementById('ingredients_expense').value) || 0;
    const sticker = parseFloat(document.getElementById('sticker_charge').value) || 0;
    const sellingPrice = parseFloat(document.getElementById('price_per_bottle').value) || 0;
    const totalQty = parseFloat(document.getElementById('total_quantity').value) || 0;
    const bottleVol = parseFloat(document.getElementById('bottle_volume').value) || 1;
    const bottleCost = parseFloat(document.getElementById('bottle_cost').value) || 0;
    const travelCost = parseFloat(document.getElementById('travel_cost').value) || 0;

    const totalBottles = Math.floor(totalQty / bottleVol);

    // Total expense = ingredients + travel + (bottle cost + sticker) * total bottles
    const totalExpense = ingredients + travelCost + (bottleCost + sticker) * totalBottles;

    const costPerBottle = totalExpense / totalBottles;
    const profitPerBottle = sellingPrice - costPerBottle;
    const totalProfit = profitPerBottle * totalBottles;

    // Create a result table
    const tableHTML = `
      <table class="table table-bordered mt-3">
        <thead class="table-light">
          <tr>
            <th>Total Bottles</th>
            <th>Total Expense (₹)</th>
            <th>Cost / Bottle (₹)</th>
            <th>Selling Price (₹)</th>
            <th>Profit / Bottle (₹)</th>
            <th>Total Profit (₹)</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>${totalBottles}</td>
            <td>${totalExpense.toFixed(2)}</td>
            <td>${costPerBottle.toFixed(2)}</td>
            <td>${sellingPrice.toFixed(2)}</td>
            <td>${profitPerBottle.toFixed(2)}</td>
            <td>${totalProfit.toFixed(2)}</td>
          </tr>
        </tbody>
      </table>
    `;

    document.getElementById('resultTable').innerHTML = tableHTML;
}
   
</script>

@endsection