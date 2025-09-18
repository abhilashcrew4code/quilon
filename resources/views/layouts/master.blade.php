<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="{{ asset('assets/') }}/" data-template="vertical-menu-template">
{{-- ../../assets/ --}}

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title> @if($site_title !== '') {{ $site_title }} @else {{ config('app.name', 'Laravel') }}  @endif | @yield('title')</title>

    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/materialdesignicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/rtl/theme-default.css') }}"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/swiper/swiper.css') }}" />


    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-statistics.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/cards-analytics.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}">

    @yield('css')

    <style type="text/css">
        /* Start by setting display:none to make this hidden.
            Then we position it in relation to the viewport window
            with position:fixed. Width, height, top and left speak
            for themselves. Background we set to 80% white with
            our animation centered, and no-repeating */
        .pageModal {
            display: none;
            position: fixed;
            z-index: 1060;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(255, 255, 255, .8) url("{{ asset('assets/dist/img/loading.gif') }}") 50% 50% no-repeat;
        }

        /* When the body has the loading class, we turn
            the scrollbar off with overflow:hidden */
        body.loading .pageModal {
            overflow: hidden;
        }

        /* Anytime the body has the loading class, our
            modal element will be visible */
        body.loading .pageModal {
            display: block;
        }
    </style>
    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    {{-- <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script> --}}
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script>
        function setInputFilter(textbox, inputFilter, errMsg) {
        [ "input", "keydown", "keyup", "mousedown", "mouseup", "select", "contextmenu", "drop", "focusout" ].forEach(function(event) {
            textbox.addEventListener(event, function(e) {
            if (inputFilter(this.value)) {
                // Accepted value.
                if ([ "keydown", "mousedown", "focusout" ].indexOf(e.type) >= 0){
                this.classList.remove("input-error");
                this.setCustomValidity("");
                }

                this.oldValue = this.value;
                this.oldSelectionStart = this.selectionStart;
                this.oldSelectionEnd = this.selectionEnd;
            }
            else if (this.hasOwnProperty("oldValue")) {
                // Rejected value: restore the previous one.
                this.classList.add("input-error");
                this.setCustomValidity(errMsg);
                this.reportValidity();
                this.value = this.oldValue;
                this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
            }
            else {
                // Rejected value: nothing to restore.
                this.value = "";
            }
            });
        });
    }
    </script>



</head>

<body>

    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            @yield('sidemenu')
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="mdi mdi-menu mdi-24px"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item navbar-search-wrapper mb-0">

                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- Language -->
                           
                            <!--/ Language -->
            
                            <!-- Style Switcher -->
                            <li class="nav-item d-none d-sm-inline-block">
                                <span class=" text-uppercase text-bold font-weight-bold "><strong>
                                    Quilon Pickles</strong></span>
                            </li>

            
                            <!-- Notification -->
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                             
                            
                            </li>
                            <!--/ Notification -->
            
                            <!-- User -->
                           
                            <!--/ User -->
                          </ul>

                        <ul class="navbar-nav flex-row align-items-center ms-auto" id="topMenu">
                            <!-- Language -->
                            @impersonating
                            <li class="nav-item">
                                <a href="{{ route('user.impersonate.stop') }}" class="nav-link" data-toggle='tooltip' data-placement="top" title="Leave Impersonation"><strong>Stop Impersonate</strong></a>
                            </li>
                             @endImpersonating
                            @php
                            $hr_mn_se = \Carbon\Carbon::parse(now())->format('H:i:s');
                            $result = strtotime($hr_mn_se) - strtotime('today');
                            @endphp
                            <input type="hidden" value="{{ $result }}" id="hr_mn_se_seconnds" />
                            <input type="hidden" @if($timezone !== '') value="{{ $timezone }}" @else value="Asia/Kolkata" @endif id="timezone" />
                            <li class="nav-item dropdown-language dropdown me-1 me-xl-0">
                                <a class="nav-link  waves-effect" data-toggle="dropdown"> 
                                    {{-- {{ \Carbon\Carbon::parse(now())->format('d-m-Y') }}  --}}
                                    <span id="hr_mn_se_timer"></span>
                                </a>
                            </li>

                            @if(isset($expiry_msg) && $expiry_msg!=='')
                            <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-2 me-xl-1">
                                <a class="nav-link btn btn-text-secondary rounded-pill btn-icon dropdown-toggle hide-arrow"
                                    href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                    aria-expanded="false">
                                    <i class="mdi mdi-bell-outline mdi-24px"></i>
                                    <span
                                        class="position-absolute top-0 start-50 translate-middle-y badge badge-dot bg-danger mt-2 border"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end py-0">
                                    <li class="dropdown-menu-header border-bottom">
                                        <div class="dropdown-header d-flex align-items-center py-3">
                                            <h6 class="mb-0 me-auto">Notification</h6>
                                        </div>
                                    </li>
                                    <li class="dropdown-notifications-list scrollable-container">
                                        <ul class="list-group list-group-flush">
                                            <li
                                                class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                <div class="d-flex gap-2">
                                                    <div class="flex-shrink-0">
                                                        <div class="avatar me-1">
                                                            <span
                                                                class="avatar-initial rounded-circle bg-label-warning"><i
                                                                    class="mdi mdi-alert-circle-outline"></i></span>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="d-flex flex-column flex-grow-1 overflow-hidden w-px-200">
                                                        <h6 class="mb-1">Subscription Expiring Soon</h6>
                                                        <small class="text-truncate text-body">{{$expiry_msg}}</small>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </li>

                                </ul>
                            </li>
                            @endif


                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt
                                            class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <span class="fw-medium d-block">{{ Auth::user()->name }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    @if(auth()->user()->can('password.change'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user-profile') }}">
                                            <i class="mdi mdi-account-outline me-2"></i>
                                            <span class="align-middle">My Profile</span>
                                        </a>
                                    </li>
                                    @endif
                                    @if(auth()->user()->can('password.change'))
                                    <li>
                                        <a class="dropdown-item" href="{{ route('password-change') }}">
                                            <i class="mdi mdi-cog-outline me-2"></i>
                                            <span class="align-middle">Change Password</span>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    @endif
                                   

                                    @impersonating
                                    <li>
                                        <a class="dropdown-item" href="{{ route('user.impersonate.stop') }}">
                                            <i class="mdi mdi-eye-remove me-2"></i>
                                            <span class="align-middle">Leave Impersonation</span>
                                        </a>
                                    </li>
                                    @endImpersonating




                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout')
                                            }}</a>
                        
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </div>

                                    
                                    <li>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="mdi mdi-logout me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>

                    <!-- Search Small Screens -->
                    <div class="navbar-search-wrapper search-input-wrapper d-none">
                        <input type="text" class="form-control search-input container-xxl border-0"
                            placeholder="Search..." aria-label="Search..." />
                        <i class="mdi mdi-close search-toggler cursor-pointer"></i>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">

                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        @yield('content')
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-center py-3 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    Copyright &copy; @php echo date('Y') @endphp
                                </div>
                                <div class="d-none d-lg-inline-block">

                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->



                    <div class="content-backdrop fade"></div>
                </div>

                <div class="modal fade" id="modal-default">
                    <div class="modal-dialog">
                        <div class="modal-content">

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>

                <div class="modal fade" id="modal-lg">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <div class="modal fade" id="modal-xl">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content">

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>




    </div>

    <div class="pageModal">
        <!-- Place at bottom of page -->
    </div>



    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/swiper/swiper.js') }}"></script>

    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-crm.js') }}"></script>

    <script src="{{ asset('assets/js/ui-modals.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>

    {{-- <script src="../../"></script> --}}
    <!-- AdminLTE for demo purposes -->
    <script type="text/javascript">

$(document).on({
            ajaxStart: function() {
                $.blockUI({
                    message: '<div class="spinner-border text-white" role="status"></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                    overlayCSS: {
                        opacity: 0.5
                    }
                });
            },
            ajaxStop: function() {
                $.unblockUI();
            }
        });

        $(document).ajaxComplete(function myErrorHandler(event, xhr, ajaxOptions, thrownError) {
            if (xhr.status === 401 || xhr.status === 419) {
                toastr.error('Session Expired. Redirecting...')

                setTimeout(function() {
                    location.reload(true);
                }, 2000);
            } else if (xhr.status === 422) {
                // console.log(xhr.responseJSON.errors);
                // var err_resp = JSON.parse(xhr.responseJSON);

                $.each(xhr.responseJSON.errors, function(key, value) {
                    toastr.error(value);
                });

            } else if (xhr.status === 500) {
                toastr.error('Some error occured. Please contact administrator.')
            }
            // alert("Ajax request completed with response code " + xhr.status);
        });
        
        var timerVar = setInterval(countTimer, 1000);
            var totalSeconds = document.getElementById('hr_mn_se_seconnds').value;

            function isNumberKey(evt){
            var charCode = (evt.which) ? evt.which : evt.keyCode;
            //if (charCode > 31 && (charCode != 46 &&(charCode < 48 || charCode> 57)))//46 '.'
                if (charCode > 31 && ((charCode < 48 || charCode> 57)))
                    return false;
                    return true;
                    }

            function countTimer() {
                // ++totalSeconds;
                // var hour = Math.floor(totalSeconds /3600);
                // var minute = Math.floor((totalSeconds - hour*3600)/60);
                // var seconds = totalSeconds - (hour*3600 + minute*60);
                // if(hour.toString().length == 1)
                // {
                //     var hour='0' + hour;
                // }
                // if(minute.toString().length == 1)
                // {
                //     var minute='0' + minute;
                // }
                // if(seconds.toString().length == 1)
                // {
                //     var seconds='0' + seconds;
                // }


                var currentTimeUTC = new Date();

                var configTimezone = document.getElementById('timezone').value;

                var options = { timeZone: configTimezone, hour12: false };
                var timeInZone = currentTimeUTC.toLocaleString('en-US', options);

                var formatter = new Intl.DateTimeFormat('en', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',
                    second: 'numeric',
                    hour12: false,
                    timeZone: configTimezone
                });

                var parts = formatter.formatToParts(new Date());
                var formattedDate = parts[2].value + '-' + parts[0].value + '-' + parts[4].value + ' ' + parts[6].value + ':' + parts[8].value + ':' + parts[10].value;

                document.getElementById("hr_mn_se_timer").innerHTML = formattedDate;

            }

            // $('[data-toggle="tooltip"]').tooltip();
    </script>
    @yield('js')
</body>

</html>