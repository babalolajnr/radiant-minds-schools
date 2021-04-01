    <!-- Well begun is half done. - Aristotle -->
    <aside class="main-sidebar main-sidebar-custom sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/dashboard" class="brand-link d-flex justify-content-center">
            <span class="brand-text font-weight-bold">{{ config('app.name', 'App Name') }}</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-center">
                <i class="fas fa-user text-white"></i>
                <a href="{{ route('user.show', ['user' => Auth::user()]) }}" class="pl-2">{{ Auth::user()->first_name }}</a>
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
                                <a href="{{ route('academic-session.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-calendar"></i>
                                    <p>Academic Sessions</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('classroom.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-chalkboard"></i>
                                    <p>Classrooms</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('term.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-clock"></i>
                                    <p>Terms</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('subject.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-book"></i>
                                    <p>Subjects</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('teacher.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                    <p>Teachers</p>
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
                                <a href="{{ route('student.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-user-graduate"></i>
                                    <p>Students</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('student.create') }}" class="nav-link">
                                    <i class="nav-icon fas fa-plus"></i>
                                    <p>New Student</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('student.get.alumni') }}" class="nav-link">
                                    <span class="nav-icon"><i class="fa fa-history pr-2 text-sm"></i><i
                                            class="fas fa-user-graduate"></i></span>
                                    <p>Alumni</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('pd-type.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-biking"></i>
                                    <p>Pychomotor domains</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('ad-type.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-brain"></i>
                                    <p>Affective domains</p>
                                </a>
                            </li>

                        </ul>
                    </li>
                    @if (Auth::user()->isMaster())
                        <li class="nav-item menu-is-opening menu-open">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    App Management
                                    <i class="fas fa-angle-left right"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview" style="display: block;">
                                <li class="nav-item">
                                    <a href="{{ route('user.index') }}" class="nav-link">
                                        <i class="nav-icon fas fa-users"></i>
                                        <p>Users</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->

        <div class="sidebar-custom d-flex">
            <form action="/logout" method="post">
                @csrf
                <button type="submit" class="btn btn-link" title="Logout"><i
                        class="fas fa-sign-out-alt text-danger"></i></button>
            </form>
            <a href="#" class="btn btn-secondary hide-on-collapse pos-right">Help</a>
        </div>
        <!-- /.sidebar-custom -->
    </aside>
