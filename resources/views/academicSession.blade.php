<x-app-layout>
    <x-slot name="styles">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/toastr/toastr.min.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

        <!-- Tempusdominus Bootstrap 4 -->
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <span id="success" {{ session('success') ? 'data-success = true' : false }}
            data-success-message='{{ json_encode(session('success')) }}'></span>
        <span id="error" {{ session('error') ? 'data-error = true' : false }}
            data-error-message='{{ json_encode(session('error')) }}'></span>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Academic Sessions</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
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
                                <h3 class="card-title">New Academic Session</h3>
                            </div>
                            <form id="addAcademicSession" method="POST"
                                action="{{ route('academic-session.store') }}">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Academic Session">Academic Session</label>
                                        <input type="text" name="name" value="{{ old('name') }}"
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
                                                data-target="#startDate" value="{{ old('start_date') }}"
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
                                                data-target="#endDate" value="{{ old('end_date') }}"
                                                name="end_date" />
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

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Academic Sessions</h3>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($academicSessions as $academicSession)
                                            <tr>
                                                <td>
                                                    {{ $academicSession->name }}
                                                    @if ($academicSession->isCurrentAcademicSession()) <span
                                                            class="pl-3" title="Current Academic Session"><i
                                                                class="fas fa-check text-green-600"></i></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    {{ $academicSession->start_date }}
                                                </td>
                                                <td>
                                                    {{ $academicSession->end_date }}
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a
                                                            href="{{ route('academic-session.edit', ['id' => $academicSession]) }}">
                                                            <button type="button" class="btn btn-default btn-flat"
                                                                title="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </button>
                                                        </a>
                                                        {{-- render if user is authorized to delete --}}
                                                        @can('delete', $academicSession)
                                                            <button type="button" class="btn btn-danger btn-flat"
                                                                title="Delete"
                                                                onclick="deleteConfirmationModal('{{ route('academic-session.destroy', ['id' => $academicSession]) }}', '{{ $academicSession->name }}')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endcan

                                                        {{-- render if user is not authorized to delete --}}
                                                        @cannot('delete', $academicSession)
                                                        <button type="submit" class="btn btn-danger btn-flat"
                                                            title="Delete" disabled>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        @endcannot

                                                        @if ($academicSession->isCurrentAcademicSession())
                                                            <button type="button" class="btn btn-default btn-flat"
                                                                title="Set as current academic session" disabled>
                                                                <i class="fas fa-toggle-on text-green-700"></i>
                                                            </button>
                                                        @else
                                                            <a
                                                                href="{{ route('academic-session.set.current', ['id' => $academicSession]) }}">
                                                                <button type="button" class="btn btn-default btn-flat"
                                                                    title="Set as current academic session">
                                                                    <i class="fas fa-toggle-off text-red-500"></i>
                                                                </button>
                                                            </a>
                                                        @endif

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Name</th>
                                            <th>Start Date</th>
                                            <th>End Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->
    </div>
    <div class="modal fade" id="deleteConfirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <span id="deleteItemName" class="font-bold"></span>?
                </div>
                <div class="modal-footer justify-content-between">
                    <form action="" method="POST" id="yesDeleteConfirmation">
                        @method('DELETE')
                        @csrf
                        <button type="submit" class="btn btn-danger">Yes</button>
                    </form>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <x-slot name="scripts">
        <!-- Toastr -->
        <script src="{{ asset('TAssets/plugins/toastr/toastr.min.js') }}"></script>
        <!-- DataTables  & Plugins -->
        <script src="{{ asset('TAssets/plugins/datatables/jquery.dataTables.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/pdfmake/vfs_fonts.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.html5.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.print.min.js') }}">
        </script>
        <script src="{{ asset('TAssets/plugins/datatables-buttons/js/buttons.colVis.min.js') }}">
        </script>
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

            function deleteConfirmationModal(url, name) {
                $('#yesDeleteConfirmation').attr("action", url)
                $('#deleteItemName').html(name)
                $('#deleteConfirmationModal').modal('show')
            }

            $(function() {
                let Success = document.getElementById('success')
                let Error = document.getElementById('error')

                // if data-success = 'true' display alert
                if (Success.dataset.success == 'true')
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        subtitle: 'Close',
                        body: JSON.parse(Success.dataset.successMessage)
                    })
                if (Error.dataset.error == 'true')
                    $(document).Toasts('create', {
                        class: 'bg-danger',
                        title: 'Error',
                        subtitle: 'Close',
                        body: JSON.parse(Error.dataset.errorMessage)
                    })


            });
            $(function() {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');

            });

        </script>
    </x-slot>
</x-app-layout>
