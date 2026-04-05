<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Dashboard') - {{ config('app.name', 'Satria') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/css/sidebar-menu.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/google-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/sweetalert.css') }}">

    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">

    @stack('css')

    <style>
        [x-cloak] {
            display: none !important;
        }

        .menu-link.menu-toggle::before,
        .menu-link::before {
            display: none;
        }
    </style>
</head>

<body class="boxed-size">
    <script>
        (function () {
            try {
                if (localStorage.getItem('trezo_theme') === 'dark') {
                    document.body.setAttribute('data-theme', 'dark');
                }
            } catch (e) {}
        })();
    </script>

    <div class="preloader" id="preloader">
        <div class="preloader">
            <div class="waviy position-relative">
                <span class="d-inline-block">S</span>
                <span class="d-inline-block">A</span>
                <span class="d-inline-block">T</span>
                <span class="d-inline-block">R</span>
                <span class="d-inline-block">I</span>
                <span class="d-inline-block">A</span>
            </div>
        </div>
    </div>

    @php
        $currentUserName = (string) optional(Auth::user())->name;
        $isPpicUser = in_array($currentUserName, ['ppic1', 'ppic2', 'ppic3'], true);
        $isFinanceUser = in_array($currentUserName, ['finance1', 'finance2', 'finance3'], true);
    @endphp

    <div class="sidebar-area" id="sidebar-area">
        <div class="logo position-relative">
            <a href="{{ url('/dashboard') }}" class="d-block text-decoration-none position-relative">
                <img src="https://logodix.com/logo/1017132.png" width="30" height="30" alt="logo-icon">
                <span class="logo-text fw-bold text-dark">{{ config('app.name', 'Satria') }}</span>
            </a>
            <button class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y" id="sidebar-burger-menu">
                <i data-feather="x"></i>
            </button>
        </div>

        <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
            <ul class="menu-inner">
                @auth
                    <li class="menu-title small text-uppercase">
                        <span class="menu-title-text">MAIN</span>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('dashboard.index') }}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">dashboard</span>
                            <span class="title">All SJ/DO</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('dashboard.outstanding') }}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">warning</span>
                            <span class="title">SJ/DO > 7 Day PPIC</span>
                        </a>
                    </li>

                    @if($isFinanceUser)
                        <li class="menu-item">
                            <a href="{{ route('dashboard.outstanding.finance') }}" class="menu-link">
                                <span class="material-symbols-outlined menu-icon">account_balance</span>
                                <span class="title">SJ/DO > 7 Day FIN</span>
                            </a>
                        </li>
                    @endif

                    <li class="menu-item">
                        <a href="{{ route('dashboard.sj.index') }}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">schedule</span>
                            <span class="title">SJ/DO < 7 Day</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('sj.upload.index') }}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">upload</span>
                            <span class="title">Upload SJ From SAP</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('invoice.index') }}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">receipt_long</span>
                            <span class="title">Upload Invoice</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('dashboard.error.index') }}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">error</span>
                            <span class="title">SJ Error</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <a href="{{ route('customer.index') }}" class="menu-link">
                            <span class="material-symbols-outlined menu-icon">group</span>
                            <span class="title">Customer</span>
                        </a>
                    </li>

                    @if($isFinanceUser)
                        <li class="menu-item">
                            <a href="{{ route('sj.create') }}" class="menu-link">
                                <span class="material-symbols-outlined menu-icon">add</span>
                                <span class="title">Create SJ</span>
                            </a>
                        </li>
                    @endif

                    @if($isPpicUser)
                        <li class="menu-item">
                            <a href="{{ route('sj.balik.index') }}" class="menu-link">
                                <span class="material-symbols-outlined menu-icon">barcode_scanner</span>
                                <span class="title">Scan SJ/DO</span>
                            </a>
                        </li>
                    @endif
                @endauth
            </ul>
        </aside>
    </div>

    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <header class="header-area bg-white mb-4 rounded-bottom-15" id="header-area">
                <div class="row align-items-center">
                    <div class="col-sm-6">
                        <div class="left-header-content">
                            <ul class="d-flex align-items-center ps-0 mb-0 list-unstyled">
                                <li>
                                    <button class="header-burger-menu bg-transparent p-0 border-0" id="header-burger-menu">
                                        <span class="material-symbols-outlined">menu</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="right-header-content mt-2 mt-sm-0">
                            <ul class="d-flex align-items-center justify-content-end ps-0 mb-0 list-unstyled">
                                <li class="header-right-item me-2">
                                    <button class="switch-toggle settings-btn dark-btn p-0 bg-transparent border-0" id="switch-toggle" type="button" aria-label="Toggle dark mode">
                                        <span class="dark"><i class="material-symbols-outlined">light_mode</i></span>
                                        <span class="light"><i class="material-symbols-outlined">dark_mode</i></span>
                                    </button>
                                </li>
                                @auth
                                    <li class="header-right-item me-3">
                                        <span class="text-secondary fw-medium">Welcome {{ Auth::user()->name }}</span>
                                    </li>
                                    <li class="header-right-item">
                                        <form method="POST" action="{{ url('/logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="ri-logout-box-r-line"></i> Logout
                                            </button>
                                        </form>
                                    </li>
                                @endauth
                            </ul>
                        </div>
                    </div>
                </div>
            </header>

            <div class="main-content-container overflow-hidden">
                @yield('content')
            </div>

            <div class="flex-grow-1"></div>

            <footer class="footer-area bg-white text-center rounded-top-7">
                <p class="fs-14">&copy; <span class="text-primary-div">{{ config('app.name', 'Satria') }}</span> {{ date('Y') }}. All rights reserved.</p>
            </footer>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('assets/js/dragdrop.js') }}"></script>
    <script src="{{ asset('assets/js/feather.min.js') }}"></script>
    <script src="{{ asset('assets/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/js/clipboard.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom/custom.js') }}"></script>

    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('js/Chart.bundle.min.js') }}"></script>
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @if (session('message'))
                swal('Success', {!! json_encode(session('message')) !!}, 'success');
            @elseif (session('warning'))
                swal('Warning', {!! json_encode(session('warning')) !!}, 'warning');
            @elseif (session('danger'))
                swal('Error', {!! json_encode(session('danger')) !!}, 'error');
            @endif
        });
    </script>

    <script>
        $("input:text:visible:first").focus();
    </script>

    <script>
        window.satriaDataTableDefaults = {
            lengthMenu: [
                [10, 25, 50, -1],
                ['10', '25', '50', 'Show all']
            ],
            dom: 'lBfrtip',
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'csvHtml5']
        };

        window.satriaAjaxConfig = function(url) {
            return {
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            };
        };

        window.initSatriaDataTable = function(selector, options) {
            var mergedOptions = $.extend(true, {}, window.satriaDataTableDefaults, options || {});
            return $(selector).DataTable(mergedOptions);
        };
    </script>

    @stack('scripts')
    @yield('page-scripts')
</body>

</html>
