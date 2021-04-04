<x-app-layout>
    <x-slot name="styles">
    </x-slot>
    <div class="content-wrapper" style="min-height: 286px;">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Dashboard</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Dashboard</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $dashboardData['students'] }}</h3>
                                <p class="font-semibold">Students</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <a href="{{ route('student.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $dashboardData['teachers'] }}</h3>

                                <p class="font-semibold">Teachers</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chalkboard-teacher"></i>
                            </div>
                            <a href="{{ route('teacher.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $dashboardData['users'] }}</h3>

                                <p class="font-semibold">Users</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <a href="{{ route('user.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ $dashboardData['classrooms'] }}</h3>

                                <p class="font-semibold">Classrooms</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-chalkboard"></i>
                            </div>
                            <a href="{{ route('classroom.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>
                                    @if ($dashboardData['academicSession'] !== null)
                                        {{ $dashboardData['academicSession']->name }}
                                    @else
                                        Not Set
                                    @endif
                                </h3>
                                <p class="font-semibold">Academic Session</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-calendar"></i>
                            </div>
                            <a href="{{ route('academic-session.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $dashboardData['subjects'] }}</h3>
                                <p class="font-semibold">Subjects</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-book"></i>
                            </div>
                            <a href="{{ route('subject.index') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ $dashboardData['alumni'] }}</h3>

                                <p class="font-semibold">Alumni</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-user-graduate"></i>
                            </div>
                            <a href="{{ route('student.get.alumni') }}" class="small-box-footer">More info <i
                                    class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->

                    <!-- ./col -->
                </div>
                <div class="row">
                    <section class="col-lg-7 connectedSortable ui-sortable">
                        <!-- Custom tabs (Charts with tabs)-->
                        <div class="card">
                            <div class="card-header ui-sortable-handle" style="cursor: move;">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Classrooms Population
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#class-population-chart"
                                                data-toggle="tab">Donut</a>
                                        </li>
                                    </ul>
                                </div>
                            </div><!-- /.card-header -->
                            <div class="card-body">
                                <div class="tab-content p-0">
                                    {{-- classroom population chart --}}
                                    <div class="chart tab-pane active" id="class-population-chart"
                                        style="position: relative; height: 300px;">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas id="class-population" height="300"
                                            style="height: 300px; display: block; width: 578px;" width="578"
                                            class="chartjs-render-monitor"></canvas>

                                    </div>
                                </div>
                            </div><!-- /.card-body -->
                        </div>
                        <!-- /.card -->
                        <!-- /.card -->
                    </section>
                </div>
                <!-- /.row -->
                <!-- Main row -->

                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
    <x-slot name="scripts">
        <!-- ChartJS -->
        <script src="{{ asset('TAssets/plugins/chart.js/Chart.bundle.min.js') }}"></script>
        <script>
            const ctx = document.getElementById('class-population').getContext('2d');
            const data = {
                labels: @json($dashboardData['classroomPopulationChartData']['classroomNames']),
                datasets: [{
                    label: 'Classroom Population',
                    data: @json($dashboardData['classroomPopulationChartData']['populations']),
                    backgroundColor: @json($dashboardData['classroomPopulationChartData']['colors']),
                    hoverOffset: 4
                }]
            };
            const myChart = new Chart(ctx, {
                type: 'doughnut',
                data: data,
            });

        </script>
    </x-slot>
</x-app-layout>
