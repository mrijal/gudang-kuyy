@push('sidebar-content')

    <aside class="left-sidebar">
      <!-- Sidebar scroll-->
      <div>
        <div class="brand-logo d-flex align-items-center justify-content-between">
          <a href="{{url('/')}}" class="text-nowrap logo-img">
            <img src="/assets/images/logos/logo.png" width="100" class="me-3" alt="" />
            <b>Gudang Kuyy</b>
          </a>
          <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
            <i class="ti ti-x fs-8"></i>
          </div>
        </div>
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
          <ul id="sidebarnav">
            @if (Auth::user() && (Auth::user()->hasRole('Super Admin') || Auth::user()->hasRole('Admin')))
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Home</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{url('/')}}" aria-expanded="false">
                  <span>
                    <i class="ti ti-layout-dashboard"></i>
                  </span>
                  <span class="hide-menu">Dashboard</span>
                </a>
              </li>
            @endif

            @if (auth()->user()->can('view-barang_masuk'))
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Barang Masuk</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url('barang-masuk')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Data Barang Masuk</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url('laporan-barang-masuk')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Laporan Barang Masuk</span>
              </a>
            </li>
            @endif
            @if (auth()->user()->can('view-supplier'))
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Supplier</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url('supplier')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-users"></i>
                </span>
                <span class="hide-menu">Data Supplier</span>
              </a>
            </li>
            @endif
            {{-- <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-forms.html" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Forms</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="./ui-typography.html" aria-expanded="false">
                <span>
                  <i class="ti ti-typography"></i>
                </span>
                <span class="hide-menu">Typography</span>
              </a>
            </li> --}}
            @can('view-barang_keluar')
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Penjualan</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{url('barang-keluar')}}" aria-expanded="false">
                  <span>
                    <i class="ti ti-article"></i>
                  </span>
                  <span class="hide-menu">Data Penjualan</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="{{url('laporan-barang-keluar')}}" aria-expanded="false">
                  <span>
                    <i class="ti ti-file-description"></i>
                  </span>
                  <span class="hide-menu">Laporan Penjualan</span>
                </a>
              </li>
            @endcan
            {{-- <li class="sidebar-item">
              <a class="sidebar-link" href="" aria-expanded="false">
                <span>
                  <i class="ti ti-cash"></i>
                </span>
                <span class="hide-menu">POS</span>
              </a>
            </li> --}}
            @canany(['view-product', 'view-opname'])
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">Stock</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url('gudang')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-building-warehouse"></i>
                </span>
                <span class="hide-menu">Stock Gudang</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url('stock-opname')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-article"></i>
                </span>
                <span class="hide-menu">Stock Opname</span>
              </a>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url('laporan-stock-opname')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-file-description"></i>
                </span>
                <span class="hide-menu">Laporan Opname</span>
              </a>
            </li>
            @endcanany
            @canany(['view-user'])
            <li class="nav-small-cap">
              <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
              <span class="hide-menu">User Management</span>
            </li>
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url('user')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-user"></i>
                </span>
                <span class="hide-menu">Data User</span>
              </a>
            </li>
            @canany(['view-role', 'view-user'])
            <li class="sidebar-item">
              <a class="sidebar-link" href="{{url('role')}}" aria-expanded="false">
                <span>
                  <i class="ti ti-key"></i>
                </span>
                <span class="hide-menu">Role</span>
              </a>
            </li>
            @endcanany
            @endcanany
          </ul>
        </nav>
        <!-- End Sidebar navigation -->
      </div>
      <!-- End Sidebar scroll-->
    </aside>
@endpush