<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('dashboard') }}">Kasirquu</a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('dashboard') }}">Ka</a>
        </div>
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a href="{{ route('dashboard') }}" class="nav-link">
                    <i class="fas fa-fire"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            @if(auth()->user()->role === 'pemilik')
                <li class="menu-header">User Management</li>
                <li class="{{ request()->routeIs('users.index') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="fas fa-users"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>

                <li class="menu-header">Master Data</li>
                <li class="{{ request()->is('kategori*') || request()->is('barang*') || request()->is('diskon*') ? 'active' : '' }} dropdown">
                    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                        <i class="fas fa-database"></i>
                        <span>Master</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="{{ request()->routeIs('barang.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('barang.index') }}">
                                <i class="fas fa-box"></i> <span>Barang</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('kategori.index') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('kategori.index') }}">
                                <i class="fas fa-tags"></i> <span>Kategori</span>
                            </a>
                        </li>
                        <li class="{{ request()->routeIs('diskon.index') ? 'active' : '' }}">
                            <a href="{{ route('diskon.index') }}" class="nav-link">
                                <i class="fas fa-percent"></i> <span>Diskon</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="menu-header">Log Aktivitas</li>
                <li class="{{ request()->routeIs('log.index') ? 'active' : '' }}">
                    <a href="{{ route('log.index') }}" class="nav-link">
                        <i class="fas fa-history"></i>
                        <span>Log Aktivitas</span>
                    </a>
                </li>   
            @endif

            <li class="menu-header">Manajemen Kasir</li>
            <li class="{{ request()->routeIs('kasir.index') ? 'active' : '' }}">
                <a href="{{ route('kasir.index') }}" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Kasir</span>
                </a>
            </li>

            <li class="menu-header">Laporan</li>
            <li class="{{ request()->is('laporan*') ? 'active' : '' }} dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                    <i class="fas fa-chart-line"></i>
                    <span>Laporan</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ request()->routeIs('laporan.index') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('laporan.index') }}">
                            <i class="fas fa-file-invoice-dollar"></i> <span>Transaksi</span>
                        </a>
                    </li>
                    
                    @if(auth()->user()->role === 'pemilik')
                        <li class="{{ request()->routeIs('barang.laporan') ? 'active' : '' }}">
                            <a class="nav-link" href="{{ route('barang.laporan') }}">
                                <i class="fas fa-box"></i> <span>Stok</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            
    </aside>
</div>
