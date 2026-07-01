<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') | BI Transjakarta</title>
    
    <!-- Favicon -->
    <link rel="icon" href="https://cdn-icons-png.flaticon.com/512/3448/3448339.png" type="image/png">

    <!-- AdminLTE & Bootstrap via CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
    <style>
        .brand-link { background-color: #0d6efd; color: white !important; }
        .main-sidebar { background-color: #343a40; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; }
        
        /* Hover Effects */
        .card, .info-box, .small-box { transition: transform 0.2s ease, box-shadow 0.2s ease; border-radius: 0.5rem; }
        .card:hover, .info-box:hover, .small-box:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important; }
        .btn { transition: all 0.3s ease; }
        
        /* KPI Icon Size Fix */
        .small-box .icon { font-size: 30px !important; top: 12px !important; right: 8px !important; opacity: 0.3 !important; }
        .small-box .icon > i { font-size: 30px !important; }
        .nav-sidebar .nav-link { transition: background-color 0.2s ease, color 0.2s ease; }
        
        /* Scroll to Top */
        #back-to-top {
            position: fixed;
            bottom: 25px;
            right: 25px;
            display: none;
            z-index: 9999;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            text-align: center;
            line-height: 26px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        
        /* Skeleton Loading Placeholder */
        .skeleton-loader {
            width: 100%;
            height: 100%;
            min-height: 250px;
            background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 50%, #e0e0e0 75%);
            background-size: 200% 100%;
            animation: skeletonLoading 1.5s infinite;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
        }
        @keyframes skeletonLoading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Preloader -->
    <div class="preloader flex-column justify-content-center align-items-center bg-white">
        <img class="animation__wobble" src="https://cdn-icons-png.flaticon.com/512/3448/3448339.png" alt="Transjakarta Logo" height="60" width="60">
        <p class="mt-3 font-weight-bold text-primary" style="font-size: 1.2rem; letter-spacing: 1px;">Loading Dashboard...</p>
    </div>

    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2 align-items-center">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark font-weight-bold">@yield('title')</h1>
                    </div>
                    <div class="col-sm-6 text-right">
                        @if(!request()->routeIs('dashboard.index'))
                            <a href="{{ route('dashboard.index') }}" class="btn btn-outline-primary btn-sm shadow-sm" aria-label="Kembali ke Dashboard">
                                <i class="fas fa-arrow-left mr-1"></i> Dashboard
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <section class="content pb-4">
            <div class="container-fluid">
                @yield('content')
            </div>
        </section>
    </div>

    @include('partials.footer')
    
    <!-- Scroll To Top Button -->
    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top" title="Kembali ke atas">
        <i class="fas fa-chevron-up"></i>
    </a>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- DataTables & Plugins -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
    $(document).ready(function() {
        // Scroll to Top Logic
        $(window).scroll(function() {
            if ($(this).scrollTop() > 300) {
                $('#back-to-top').fadeIn();
            } else {
                $('#back-to-top').fadeOut();
            }
        });
        $('#back-to-top').click(function(e) {
            e.preventDefault();
            $('html, body').animate({scrollTop: 0}, 500, 'swing');
            return false;
        });

        // Toast Notification for Filter Logic
        toastr.options = { "timeOut": "3000", "positionClass": "toast-top-right", "progressBar": true };
        const urlParams = new URLSearchParams(window.location.search);
        
        // Cek jika filter diterapkan
        if (urlParams.toString().length > 0) {
            toastr.success('Filter berhasil diterapkan', 'Sukses');
        }
        
        // Listen to Reset button click to show toast (it triggers navigation, so we use sessionStorage)
        $('.btn-outline-secondary[href="{{ url()->current() }}"]').on('click', function() {
            sessionStorage.setItem('filterReset', 'true');
        });
        
        if (sessionStorage.getItem('filterReset') === 'true') {
            if (urlParams.toString().length === 0) {
                toastr.info('Filter berhasil direset', 'Info');
                sessionStorage.removeItem('filterReset');
            }
        }

        // Animated KPI Counter
        $('.kpi-counter').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text().replace(/,/g, '').replace(/\./g, '')
            }, {
                duration: 1500,
                easing: 'swing',
                step: function (now) {
                    $(this).text(new Intl.NumberFormat('id-ID').format(Math.ceil(now)));
                }
            });
        });
    });
</script>
@stack('scripts')
</body>
</html>
