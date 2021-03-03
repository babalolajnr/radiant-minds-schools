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
                        <h1>{{ $classroom->name }}</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Layout</a></li>
                            <li class="breadcrumb-item active">Fixed Layout</li>
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
                        {{-- Teacher --}}
                        <div class="card">
                            <div class="card-header">
                                <span class="font-semibold">Class teacher</span>
                            </div>
                            <div class="card-body">
                                <span class="">
                                    @if ($classroom->teacher)
                                        {{ $classroom->teacher->first_name . ' ' . $classroom->teacher->last_name }}
                                    @endif
                                </span>
                            </div>
                        </div>
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <span class="font-semibold">Students</span>
                            </div>
                            <div class="card-body">
                                <table id="example1" class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Admission No</th>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Sex</th>
                                            <th>Guardian</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; ?>
                                        @foreach ($students as $student)
                                            <tr>
                                                <td>
                                                    <?php
                                                    echo $no;
                                                    $no++;
                                                    ?>
                                                </td>
                                                <td>{{ $student->admission_no }}</td>
                                                <td>
                                                    {{ $student->first_name }}
                                                </td>
                                                <td>
                                                    {{ $student->last_name }}
                                                </td>
                                                <td>
                                                    {{ $student->sex }}
                                                </td>
                                                <td>
                                                    {{ $student->guardian->title . ' ' . $student->guardian->first_name . ' ' . $student->guardian->last_name }}
                                                </td>
                                                <td>{{ $student->status }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="/view/student/{{ $student->admission_no }}">
                                                            <button type="button" id="" class="btn btn-default btn-flat"
                                                                title="Student detailed view">
                                                                <i class="fas fa-eye"></i>
                                                            </button>
                                                        </a>

                                                        {{-- render if user is authorized to delete --}}
                                                        @can('delete', $student)
                                                            <button type="submit" class="btn btn-default btn-flat"
                                                                title="Delete"
                                                                onclick="deleteConfirmationModal({{ $student }})">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        @endcan

                                                        {{-- render if user is not authorized to delete --}}
                                                        @cannot('delete', $student)
                                                        <button type="submit" class="btn btn-default btn-flat"
                                                            title="Delete" disabled>
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                        @endcannot

                                                        <a href="/studentSettings/{{ $student->admission_no }}">
                                                            <button type="button" class="btn btn-default btn-flat">
                                                                <i class="fas fa-cogs"></i>
                                                            </button>
                                                        </a>

                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>S/N</th>
                                            <th>Admission No</th>
                                            <th>First name</th>
                                            <th>Last name</th>
                                            <th>Sex</th>
                                            <th>Guardian</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="card col-lg-6">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-baseline">
                                    <span class="font-semibold">Subjects</span>
                                    <span>
                                        <a href="/set/classroom-subjects/{{ $classroom->id }}"><button
                                                class="btn btn-primary">Set Subjects</button>
                                        </a>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach ($subjects as $subject)
                                    <div class="callout callout-info">
                                        <span>{{ $subject->name }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
        </section>
        <!-- /.content -->
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
        <!-- AdminLTE App -->
        <script>
            function deleteConfirmationModal(data) {
                let deleteItemUrl = '/delete/student/' + data.id
                $('#yesDeleteConfirmation').attr("action", deleteItemUrl)
                $('#deleteItemName').html(data.name)
                $('#deleteConfirmationModal').modal('show')
            }
            //launch toastr 
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

            //datatables
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
