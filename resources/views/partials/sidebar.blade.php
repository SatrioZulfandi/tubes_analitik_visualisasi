<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard.index') }}" class="brand-link text-center border-bottom-0">
        <i class="fas fa-bus-alt mr-2"></i>
        <span class="brand-text font-weight-bold">Transjakarta BI</span>
    </a>

    <div class="sidebar">
        <nav class="mt-3">
            <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('dashboard.index') }}" class="nav-link {{ request()->routeIs('dashboard.index', 'dashboard.index.alias') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-line"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a href="{{ route('cluster.index') }}" class="nav-link {{ request()->routeIs('cluster.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-project-diagram"></i>
                        <p>Cluster Analysis</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('operational.index') }}" class="nav-link {{ request()->routeIs('operational.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-bus"></i>
                        <p>Operational Analysis</p>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a href="{{ route('insights.index') }}" class="nav-link {{ request()->routeIs('insights.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-lightbulb"></i>
                        <p>Insights</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('recommendations.index') }}" class="nav-link {{ request()->routeIs('recommendations.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tasks"></i>
                        <p>Recommendations</p>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a href="{{ route('dataset.index') }}" class="nav-link {{ request()->routeIs('dataset.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-table"></i>
                        <p>Dataset</p>
                    </a>
                </li>

                <li class="nav-item mt-2">
                    <a href="{{ route('about.index') }}" class="nav-link {{ request()->routeIs('about.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-info-circle"></i>
                        <p>About</p>
                    </a>
                </li>

            </ul>
        </nav>
    </div>
</aside>
