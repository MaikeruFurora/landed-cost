<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>Landed Cost | AIM Inc</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="{{ csrf_token() }}" name="_token" />
        <meta content="{{ Helper::helperRight() }}" name="myRights" />
        <meta content="{{ auth()->user()->type }}" name="userType" />
        <meta content="{{ json_encode(auth()->user()->other_prev) }}" name="otherPrev" />
        <meta content="{{ json_encode(auth()->user()->getUserControl()) }}" name="initialUserControl" />
        <meta content="{{ route('authenticate.check.user.control') }}" name="checkUserControl" />
        <meta content="ThemeDesign" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <!-- App Icons -->
        <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

        @yield('moreCss')
        <link rel="stylesheet" href="{{ asset('plugins/jquery-toast/jquery.toast.css') }}">
        <link rel="stylesheet" href="{{ asset('plugins/sweet-alert2/sweetalert2.min.css') }}">
        <link href="{{ asset('plugins/aos/aos.css') }}" rel="stylesheet">

        <!-- App css -->
        <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" />

        {{-- datepicker --}}
        <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
        <link href="{{ asset('plugins/alertify/css/alertify.css') }}" rel="stylesheet">

    </head>

    {{-- transition-style="in:circle:top-right" --}}
    <body>
        {{-- @if (!(auth()->user()->type)) onmousemove="BaseModel.checkControl()" @endif --}}
        <!-- Loader -->
        @include('_layout.preloader')

        <div class="header-bg">
            <!-- Navigation Bar-->
            <header id="topnav">
                <div class="topbar-main">
                    <div class="container-fluid">

                        <!-- Logo-->
                        <div>
                            
                            <a href="#" class="logo">
                                <img src="{{ asset('assets/images/landed-icon-white.png') }}" alt="" height="35"
                                data-container="body" data-toggle="popover" data-placement="top" class="text-danger" style="font-size: 9px" data-content="{{ env("DB_HOST") }}"> 
                            </a>

                        </div>
                        <!-- End Logo-->

                        <div class="menu-extras topbar-custom navbar p-0">

                            <ul class="list-inline ml-auto mb-0">
                                
                                <!-- User-->
                                <li class="list-inline-item dropdown notification-list nav-user">
                                    <a class="nav-link dropdown-toggle arrow-none waves-effect" data-toggle="dropdown" href="#" role="button"
                                    aria-haspopup="false" aria-expanded="false">
                                        <img src="{{ asset('assets/images/avatar.png') }}" alt="user" class="rounded-circle">
                                        <span class="d-none d-md-inline-block ml-1">{{ auth()->user()->name }} <i class="mdi mdi-chevron-down"></i> </span>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-animated profile-dropdown">
                                        <div class="dropdown-divider"></div>
                                        <a style="cursor: pointer;" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="dripicons-exit text-muted"></i> Logout</a>
                                    </div>
                                </li>
                                <li class="menu-item list-inline-item">
                                    <!-- Mobile menu toggle-->
                                    <a class="navbar-toggle nav-link">
                                        <div class="lines">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </a>
                                    <!-- End mobile menu toggle-->
                                </li>

                            </ul>

                        </div>
                        <!-- end menu-extras -->

                        <div class="clearfix"></div>

                    </div> <!-- end container -->
                </div>
                <!-- end topbar-main -->

                <!-- MENU Start -->
                <div class="navbar-custom">
                    <div class="container-fluid">
                        <div id="navigation">
                            <!-- Navigation Menu-->
                            <ul class="navigation-menu">

                                <li class="has-submenu">
                                    <a href="{{ route('authenticate.details') }}"><i class="dripicons-to-do"></i> Landed Cost</a>
                                </li>

                                @if (Helper::usrChckCntrl(['OA001','AP001']))
                                    <li class="has-submenu">
                                        <a href="#"><i class="fab fa-gripfire" style="color: #2a58a7;"></i> LC & Advance Payment<i class="mdi mdi-chevron-down mdi-drop"></i></a>
                                        <ul class="submenu">
                                            @if (Helper::usrChckCntrl(['OA001']))
                                                <li><a href="{{ route('authenticate.opening.charge') }}"><i class="fas fa-wallet mr-2" style="font-size:11px"></i>LC Open Amount</a></li>
                                            @endif
                                            @if (Helper::usrChckCntrl(['AP001']))
                                                <li><a href="{{ route('authenticate.payment') }}"><i class="far fa-credit-card mr-2" style="font-size:11px"></i>Payment <b class="text-success">(New)</b></a></li>
                                            @endif
                                            @if (Helper::usrChckCntrl(['AP001']))
                                                <li><a href="{{ route('authenticate.contract') }}"><i class="far fa-credit-card mr-2" style="font-size:11px"></i>Payment <b class="text-red">(Old)</b></a></li>
                                             @endif
                                        </ul>
                                    </li>
                                @endif
                                
                                @if (Helper::usrChckCntrl(['RP001']))
                                <li class="has-submenu">
                                    <a href="{{ route('authenticate.report') }}"><i class="far fa-folder-open"></i>Report</a>
                                </li>
                                @endif
                                
                                @if (Helper::usrChckCntrl(['DB001']))
                                <li class="has-submenu">
                                    <a href="{{ route('authenticate.dollarbook') }}"><i class="fas fa-university"></i> TOF/AOD/TELE</a>
                                </li>
                                @endif

                                @access
                                
                                <li class="has-submenu">
                                    <a href="{{ route('authenticate.particular') }}"><i class="dripicons-checklist"></i> Particulars</a>
                                </li>

                                <li class="has-submenu"><a href="{{ route('authenticate.user') }}"><i class="dripicons-user"></i>Users</a></li>

                                <li class="has-submenu"><a href="{{ route('authenticate.audit.log') }}"><i class="fas fa-cogs"></i>Audit Records</a></li>

                                @endaccess

                                <li class="has-submenu">
                                    <a class="text-danger" style="cursor:pointer"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="dripicons-exit"></i>Sign out</a>
                                    <form id="logout-form" action="{{ route('authenticate.signout') }}" method="POST" class="d-none">@csrf</form>
                                </li>

                            </ul>
                            <!-- End navigation menu -->
                        </div> <!-- end #navigation -->
                    </div> <!-- end container -->
                </div> <!-- end navbar-custom -->
            </header>
            <!-- End Navigation Bar-->

        </div>
        <!-- header-bg -->

        <div class="wrapper">
            <div class="alert alert-warning p-1" style="font-size: 11px;" role="alert">
                <div class="container-fluid"><span class="ml-2">  <span class="text-dark"><b>Private beta testing</b> is exclusive to AIMI users.</span></span></div>
            </div>
            <div class="container-fluid">
                @yield('content')
            </div> <!-- end container-fluid -->
        </div>
        <!-- end wrapper -->


        <!-- Footer -->
            @include('_layout.footer')
        <!-- End Footer -->


        <!-- jQuery  -->
        <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/waves.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.slimscroll.js') }}"></script>
        {{-- datepicker --}}
        <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
        <script src="{{ asset('plugins/jquery-toast/jquery.toast.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('plugins/sweet-alert2/sweetalert2.min.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('assets/js/global.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('plugins/alertify/js/alertify.js') }}?v={{ time() }}"></script>
      
        @yield('moreJs')
        
        <!-- App js -->
        <script src="{{ asset('assets/js/app.js') }}"></script>
        <script src="{{ asset('plugins/aos/aos.js') }}"></script>
        <script>
            window.addEventListener('load', () => {
                AOS.init({
                    duration: 1000,
                    easing: 'ease-in-out',
                    once: true,
                    mirror: false
                })
            });

            $(window).on("shown.bs.modal", function() {
                AOS.init({disable:true});
            });

            $(window).on("hidden.bs.modal", function() {
                AOS.init({disable:false});
            });
        </script>
    </body>
</html>