    <!-- Well begun is half done. - Aristotle -->
    <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="../../index3.html" class="brand-link">
            <img src="{{ asset('TAssets')}}dist/img/AdminLTELogo.png" alt="AdminLTE Logo"
                class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">AdminLTE 3</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="{{ asset('TAssets')}}dist/img/user2-160x160.jpg" class="img-circle elevation-2"
                        alt="User Image">
                </div>
                <div class="info">
                    <a href="#" class="d-block">{{ Auth::user()->first_name }}</a>
                </div>
            </div>

            <!-- SidebarSearch Form -->
            <div class="form-inline">
                <div class="input-group" data-widget="sidebar-search">
                    <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                        aria-label="Search">
                    <div class="input-group-append">
                        <button class="btn btn-sidebar">
                            <i class="fas fa-search fa-fw"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                    data-accordion="false">
                    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
                    <li class="nav-item menu-is-opening menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-school"></i>
                            <p>
                                School Management
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: block;">
                            <li class="nav-item">
                                <a href="/academicSessions" class="nav-link">
                                    <i class="nav-icon fas fa-calendar"></i>
                                    <p>Academic Sessions</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/classrooms" class="nav-link">
                                    <i class="nav-icon fa fa-list-alt"  ></i>
                                    <p>Classrooms</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item menu-is-opening menu-open">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fa fa-child"></i>

                            <p>
                                Students Management
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview" style="display: block;">
                            <li class="nav-item">
                                <a href="/students" class="nav-link">
                                    <i class="nav-icon fas fa-user-graduate"></i>
                                    <p>Students</p>
                                </a>
                            </li>
                            {{-- <li class="nav-item">
                                <a href="/classrooms" class="nav-link">
                                    <i class="nav-icon fa fa-list-alt" aria-hidden="true"></i>
                                    <p>Classrooms</p>
                                </a>
                            </li> --}}
                        </ul>
                    </li>

                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->

        <div class="sidebar-custom">
            <a href="#" class="btn btn-link"><i class="fas fa-cogs"></i></a>
            <a href="#" class="btn btn-secondary hide-on-collapse pos-right">Help</a>
        </div>
        <!-- /.sidebar-custom -->
    </aside>
