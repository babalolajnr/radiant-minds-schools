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
                                <span class="font-semibold">Students</span>
                            </div>
                            <div class="card-body">
                                <x-students-table :students="$students" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-baseline">
                                            <span class="font-semibold">Class Teacher</span>
                                            <span>
                                                <button class="btn btn-primary"
                                                    onclick="showAssignTeacherModal()">Assign Teacher</button>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <span class="">
                                            @if ($classroom->teacher)
                                                {{ $classroom->teacher->first_name . ' ' . $classroom->teacher->last_name }}
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="card col-lg-6">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-baseline">
                                        <span class="font-semibold">Subjects</span>
                                        <span>
                                            <a
                                                href="{{ route('classroom.set.subjects', ['classroom' => $classroom]) }}"><button
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
                </div>
        </section>
        <!-- /.content -->
    </div>
    {{-- Delete confirmation modal --}}
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

    {{-- Assign Teacher modal --}}
    <div class="modal fade" id="assignTeacherModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Assign Teacher</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="Teachers">Teachers</label>
                        <select class="form-control select2" id="teacherSelect" style="width: 100%;">
                            @foreach ($teachers as $teacher)
                                <option @if ($classroom->teacher == $teacher) SELECTED @endif
                                    value="{{ route('classroom.assign.teacher', ['classroom' => $classroom, 'teacherSlug' => $teacher->slug]) }}">
                                    {{ "{$teacher->first_name} {$teacher->last_name}" }} @if ($teacher->classroom) {{ "({$teacher->classroom->name})" }}
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <form action="" method="POST" id="assignTeacherForm">
                        @method('PATCH')
                        @csrf
                        <button type="button" onclick="assignTeacher()" class="btn btn-success">Submit</button>
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
        <!-- AdminLTE App -->
        <script>
            function deleteConfirmationModal(url, data) {
                let name = data.first_name + ' ' + data.last_name

                $('#yesDeleteConfirmation').attr("action", url)
                $('#deleteItemName').html(name)
                $('#deleteConfirmationModal').modal('show')
            }

            function showAssignTeacherModal() {
                $('#assignTeacherModal').modal('show')
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

            function assignTeacher()
            {
                const selected = $("#teacherSelect").val()
                $("#assignTeacherForm").attr('action', selected)
                $("#assignTeacherForm").submit()
            }

        </script>
    </x-slot>
</x-app-layout>
