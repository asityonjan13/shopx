<aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
    <div class="container-fluid">
        <!-- BEGIN NAVBAR TOGGLER -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- END NAVBAR TOGGLER -->
        <!-- BEGIN NAVBAR LOGO -->
        <div class="navbar-brand navbar-brand-autodark">
            <a href="/admin/dashboard" class="d-flex justify-content-center">
                <img src="{{ asset('assets/frontend/imgs/theme/logo.png') }}" style="width: 50%;" alt="logo" />
            </a>
        </div>
        <!-- END NAVBAR LOGO -->
        <div class="navbar-nav flex-row d-lg-none">
            <div class="d-none d-lg-flex">
                <div class="nav-item">
                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode"
                        data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <i class="ti ti-moon fs-2"></i>
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode"
                        data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <i class="ti ti-sun fs-2"></i>
                    </a>
                </div>
                <div class="nav-item dropdown d-none d-md-flex">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                        aria-label="Show notifications" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ti ti-bell fs-2"></i>
                        <span class="badge bg-red"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                        <div class="card">
                            <div class="card-header d-flex">
                                <h3 class="card-title">Notifications</h3>
                                <div class="btn-close ms-auto" data-bs-dismiss="dropdown"></div>
                            </div>
                            <div class="list-group list-group-flush list-group-hoverable">
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span
                                                class="status-dot status-dot-animated bg-red d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 1</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">Change deprecated
                                                html tags to text decoration classes (#29604)</div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <i class="ti ti-star fs-2 text-muted"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 2</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">
                                                justify-content:between ⇒ justify-content:space-between (#29734)</div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions show">
                                                <i class="ti ti-star fs-2 text-yellow"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span class="status-dot d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 3</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">Update
                                                change-version.js (#29736)</div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <i class="ti ti-star fs-2 text-muted"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="list-group-item">
                                    <div class="row align-items-center">
                                        <div class="col-auto"><span
                                                class="status-dot status-dot-animated bg-green d-block"></span></div>
                                        <div class="col text-truncate">
                                            <a href="#" class="text-body d-block">Example 4</a>
                                            <div class="d-block text-secondary text-truncate mt-n1">Regenerate
                                                package-lock.json (#29730)</div>
                                        </div>
                                        <div class="col-auto">
                                            <a href="#" class="list-group-item-actions">
                                                <i class="ti ti-star fs-2 text-muted"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <a href="#" class="btn btn-2 w-100"> Archive all </a>
                                    </div>
                                    <div class="col">
                                        <a href="#" class="btn btn-2 w-100"> Mark all as read </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nav-item dropdown d-none d-md-flex me-3">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                        aria-label="Show app menu" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ti ti-apps fs-2"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">My Apps</div>
                                <div class="card-actions btn-actions">
                                    <a href="#" class="btn-action">
                                        <i class="ti ti-settings fs-2"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="card-body scroll-y p-2" style="max-height: 50vh">
                                <div class="row g-0">
                                    <div class="col-4">
                                        <a href="#"
                                            class="d-flex flex-column flex-center text-center text-secondary py-2 px-2 link-hoverable">
                                            <img src="./static/brands/amazon.svg" class="w-6 h-6 mx-auto mb-2"
                                                width="24" height="24" alt="" />
                                            <span class="h5">Amazon</span>
                                        </a>
                                    </div>
                                    <!-- Additional app items would continue here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url(./static/avatars/000m.jpg)"> </span>
                    <div class="d-none d-xl-block ps-2">
                        <div>Paweł Kuna</div>
                        <div class="mt-1 small text-secondary">UI Designer</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="#" class="dropdown-item">Status</a>
                    <a href="./profile.html" class="dropdown-item">Profile</a>
                    <a href="#" class="dropdown-item">Feedback</a>
                    <div class="dropdown-divider"></div>
                    <a href="./settings.html" class="dropdown-item">Settings</a>
                    <a href="./sign-in.html" class="dropdown-item">Logout</a>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="sidebar-menu">
            <!-- BEGIN NAVBAR MENU -->
            <ul class="navbar-nav pt-lg-3">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-home fs-2"></i>
                        </span>
                        <span class="nav-link-title"> Home </span>
                    </a>
                </li>
                {{-- Ecommerce --}}
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                        data-bs-auto-close="false" role="button" aria-expanded="false">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-shopping-cart fs-2"></i>
                        </span>
                        <span class="nav-link-title"> Ecommerce </span>
                    </a>
                    <div class="dropdown-menu">
                        <div class="dropdown-menu-columns">
                            @if (hasPermission(['Role Management']))
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="{{ route('admin.categories.index') }}">
                                        <i class="ti ti-category me-2"></i>
                                        Categories
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </li>
                {{-- Interface --}}
                @if (hasPermission(['KYC Management']))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="false" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-file-check fs-2"></i>
                            </span>
                            <span class="nav-link-title"> KYC Requests </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                <div class="dropdown-menu-column">
                                    <a class="dropdown-item" href="{{ route('admin.kyc.index') }}">
                                        <i class="ti ti-list me-2"></i>
                                        All Requests
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.kyc.approved') }}">
                                        <i class="ti ti-circle-check me-2"></i>
                                        Approved Requests
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.kyc.pending') }}">
                                        <i class="ti ti-clock me-2"></i>
                                        Pending Requests
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.kyc.rejected') }}">
                                        <i class="ti ti-circle-x me-2"></i>
                                        Rejected Requests
                                    </a>
                                </div>
                            </div>
                        </div>
                    </li>
                @endif
                {{-- Roles --}}
                @if (hasPermission(['Role Management', 'Role User Management']))
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown"
                            data-bs-auto-close="false" role="button" aria-expanded="false">
                            <span class="nav-link-icon d-md-none d-lg-inline-block">
                                <i class="ti ti-shield-lock fs-2"></i>
                            </span>
                            <span class="nav-link-title"> Access Management </span>
                        </a>
                        <div class="dropdown-menu">
                            <div class="dropdown-menu-columns">
                                @if (hasPermission(['Role Management']))
                                    <div class="dropdown-menu-column">
                                        <a class="dropdown-item" href="{{ route('admin.role.index') }}">
                                            <i class="ti ti-user-shield me-2"></i>
                                            Role
                                        </a>
                                    </div>
                                @endif
                                @if (hasPermission(['Role User Management']))
                                    <div class="dropdown-menu-column">
                                        <a class="dropdown-item" href="{{ route('admin.role-users.index') }}">
                                            <i class="ti ti-users me-2"></i>
                                            All Role User
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </li>
                @endif
                {{-- Settings --}}
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.settings.index') }}">
                        <span class="nav-link-icon d-md-none d-lg-inline-block">
                            <i class="ti ti-settings fs-2"></i>
                        </span>
                        <span class="nav-link-title"> Settings </span>
                    </a>
                </li>
            </ul>
            <!-- END NAVBAR MENU -->
        </div>
    </div>
</aside>
<!-- BEGIN NAVBAR  -->
<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-xl">
        <!-- BEGIN NAVBAR TOGGLER -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- END NAVBAR TOGGLER -->
        <div class="navbar-nav flex-row order-md-last">
            <div class="d-none d-md-flex">
                <div class="nav-item">
                    <a href="?theme=dark" class="nav-link px-0 hide-theme-dark" title="Enable dark mode"
                        data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <i class="ti ti-moon fs-2"></i>
                    </a>
                    <a href="?theme=light" class="nav-link px-0 hide-theme-light" title="Enable light mode"
                        data-bs-toggle="tooltip" data-bs-placement="bottom">
                        <i class="ti ti-sun fs-2"></i>
                    </a>
                </div>

                <div class="nav-item dropdown d-none d-md-flex me-3">
                    <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1"
                        aria-label="Show app menu" data-bs-auto-close="outside" aria-expanded="false">
                        <i class="ti ti-apps fs-2"></i>
                    </a>
                </div>
            </div>
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 p-0 px-2" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="avatar avatar-sm"
                        style="background-image: url({{ asset(auth('admin')->user()->avatar) }})"> </span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ auth('admin')->user()->name }}</div>
                        <div class="mt-1 small text-secondary">{{ auth('admin')->user()?->getRoleNames()?->first() }}
                        </div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="#" class="dropdown-item">Status</a>
                    <a href="{{ route('admin.profile.index') }}" class="dropdown-item">Profile</a>
                    <a href="#" class="dropdown-item">Feedback</a>
                    <div class="dropdown-divider"></div>
                    <a href="./settings.html" class="dropdown-item">Settings</a>
                    <a onclick="event.preventDefault();
                                $('.logout-form').submit();"
                        href="#" class="dropdown-item">Logout</a>
                    <!-- Authentication -->
                    <form class="logout-form" method="POST" action="{{ route('admin.logout') }}">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
        <div class="collapse navbar-collapse" id="navbar-menu">
            <!-- BEGIN NAVBAR MENU -->

            <!-- END NAVBAR MENU -->
        </div>
    </div>
</header>
<!-- END NAVBAR  -->
