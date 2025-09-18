@extends('layouts.master')
@extends('layouts.sidemenu')

@section('title')
Home
@endsection


@section('css')
<link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-statistics.css') }}">
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}">
@endsection

@section('content')

<div class="row gy-4">
    <div class="col-md-12 col-lg-12">
        <div class="card h-100">
            <div class="d-flex align-items-end row">
                <div class="col-md-6 order-2 order-md-1">
                    <div class="card-body">
                        <h4 class="card-title pb-xl-2">Hello <b>{{ Auth::user()->name}}</b>,</h4>
                        <p class="mb-0">Welcome to Quilon Pickles !</p>
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2 pt-2">
                            
                            @php
                                use Carbon\Carbon;

                                $validTillDate = Carbon::parse($valid_till);
                                $currentDate = Carbon::now();
                                $remainingDays = $validTillDate->diffInDays($currentDate);
                            @endphp

                                <div class="col-6">
                                    <div class="d-flex">
                                    <div class="avatar flex-shrink-0 me-2">
                                        <span class="avatar-initial rounded bg-label-primary"><i class="mdi mdi-timer-outline mdi-24px"></i></span>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 text-nowrap">Subscription Validity</h6>
                                        <small>{{$valid_till}} ({{ $remainingDays }} days)</small>
                                    </div>
                                    </div>
                                </div>
                        </div>


                        @if(isset($expiry_msg) && $expiry_msg!=='')

                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <h4 class="alert-heading d-flex align-items-center">
                                <i class="mdi mdi-alert-circle-outline mdi-24px me-2"></i>Your Subscription Is Expiring Soon!!
                            </h4>
                            <p>{{$expiry_msg}}
                            </p>
                            <hr>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>

                        @endif
                        @php
                            $user = Auth::user();
                            $password_change_msg = '';

                            if ($user) {
                                
                                $lastPasswordChange = $user->last_password_change;

                                if ($lastPasswordChange !== null && $lastPasswordChange !== '') {
                                    $currentDate = \Carbon\Carbon::now();
                                    $passwordChangeDate = \Carbon\Carbon::createFromFormat('Y-m-d', $lastPasswordChange);

                                    $daysSincePasswordChange = $currentDate->diffInDays($passwordChangeDate);

                                    if ($daysSincePasswordChange >= 170 && $daysSincePasswordChange < 180) {
                                        $password_change_msg = 'Your password was last changed '.$daysSincePasswordChange.' days ago. For security reasons, please change your password.';
                                    }
                                } else {
                                    $password_change_msg = '';
                                }
                            } else {
                                $password_change_msg = '';
                            }
                        @endphp
                       @if(isset($password_change_msg) && $password_change_msg !== '')
                       <div class="alert alert-danger alert-dismissible" role="alert">
                           <h4 class="alert-heading d-flex align-items-center">
                               <i class="mdi mdi-account-lock-outline mdi-24px me-2"></i>Kindly Change Your Password !
                           </h4>
                           <p>{{$password_change_msg}}</p>
                           <hr>
                       </div>
                   @endif
                    </div>
                </div>
                <div class="col-md-6 text-center text-md-end order-1 order-md-2">
                    <div class="card-body pb-0 px-0 px-md-4 ps-0">
                        <img src="../../assets/img/illustrations/illustration-john-light.png" height="180"
                            alt="View Profile" data-app-light-img="illustrations/illustration-john-light.png"
                            data-app-dark-img="illustrations/illustration-john-dark.png">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(auth()->user()->can('announcements'))
    <div class="col-lg-12">
        <div class="swiper-container swiper-container-horizontal swiper swiper-sales" id="swiper-marketing-sales">
          <div class="swiper-wrapper">
            @foreach ($announcements as $announcement)
            <div class="swiper-slide pb-3">
                <h5 class="mb-2">Announcements</h5>
                <div class="d-flex align-items-center mt-3">
                  <span class="d-flex align-items-center gap-2"><i class="mdi mdi-flag text-danger"></i>
                     {{$announcement->announcement}}</span>
                </div>
              </div>
           @endforeach
          </div>
          <div class="swiper-pagination"></div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('js')
<script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>
<script src="{{ asset('assets/js/app-ecommerce-dashboard.js') }}"></script>
@endsection
