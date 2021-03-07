<x-app-layout>
    <x-slot name="styles">
        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    </x-slot>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <span id="success" {{ session('success') ? 'data-success = true' : false }}
            data-success-message='{{ json_encode(session('success')) }}'></span>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Academic Sessions</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Back</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit {{ $academicSession->name }} Academic Session</h3>
                            </div>
                            <form id="editAcademicSession" method="POST"
                                action="/update/academicSessions/{{ $academicSession->id }}">
                                @csrf
                                @method('PATCH')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Academic Session">Academic Session</label>
                                        <input type="text" name="name" value="{{ old('name', $academicSession->name) }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="academicSession" placeholder="Enter Academic Session">
                                        @error('name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Start Date</label>
                                        <div class="input-group date" id="startDate" data-target-input="nearest">
                                            <input type="text"
                                                class="form-control @error('start_date') is-invalid @enderror datetimepicker-input"
                                                data-target="#startDate" value="{{ old('start_date', $academicSession->start_date) }}"
                                                name="start_date" />
                                            <div class="input-group-append" data-target="#startDate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('start_date')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>End Date</label>
                                        <div class="input-group date" id="endDate" data-target-input="nearest">
                                            <input type="text"
                                                class="form-control @error('end_date') is-invalid @enderror datetimepicker-input"
                                                data-target="#endDate" value="{{ old('end_date', $academicSession->end_date) }}" name="end_date" />
                                            <div class="input-group-append" data-target="#endDate"
                                                data-toggle="datetimepicker">
                                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                            </div>
                                        </div>
                                        @error('end_date')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>


                    </div>
                </div>
        </section>
        <!-- /.content -->
    </div>
    <x-slot name="scripts">
        <script src="{{ asset('TAssets/plugins/moment/moment.min.js') }}"></script>
        <!-- Tempusdominus Bootstrap 4 -->
        <script src="{{ asset('TAssets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}">
        </script>

        <!-- AdminLTE App -->
        <script>
            //Date range picker
            $('#startDate').datetimepicker({
                format: 'YYYY-MM-DD'
            })

            $('#endDate').datetimepicker({
                format: 'YYYY-MM-DD'
            })

        </script>
    </x-slot>
</x-app-layout>
