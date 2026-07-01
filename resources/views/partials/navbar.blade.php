<nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard.index') }}" class="nav-link font-weight-bold">Dashboard BI Transjakarta</a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-sm-inline-block">
            <span class="nav-link text-muted"><i class="far fa-calendar-alt mr-1"></i> {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</span>
        </li>
    </ul>
</nav>
