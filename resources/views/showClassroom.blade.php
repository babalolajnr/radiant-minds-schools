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
                                                        <button type="button" id="viewStudentButton"
                                                            data-admission-no={{ $student->admission_no }}
                                                            class="btn btn-default btn-flat"
                                                            onclick="showViewModal({{ $student }}, {{ $classroom }})"
                                                            title="View">
                                                            <i class="fas fa-eye"></i>
                                                        </button>
                                                        <button type="button"
                                                            onclick="showEditModal({{ $student }})"
                                                            class="btn btn-default btn-flat" title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                        {{-- render if user is authorized to delete --}}
                                                        @can('delete', $student)
                                                            <form action="/delete/student/{{ $student->id }}"
                                                                method="POST">
                                                                @method('DELETE')
                                                                @csrf
                                                                <button type="submit" class="btn btn-default btn-flat"
                                                                    title="Delete">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
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

        {{-- edit student modal --}}
        <div class="modal fade" id="editModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Edit Student</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <a href="" id="editStudentButton">
                            <button type="button" class="btn btn-success" title="Edit Student">Student</button>
                        </a>
                        <span class="px-3"></span>
                        <a href="" id="editGuardianButton">
                            <button type="button" class="btn btn-info" title="Edit Guardian">Guardian</button>
                        </a>

                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{-- /edit student modal --}}

        {{-- sessional result modal --}}
        <div class="modal fade" id="sessionalResultModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Choose Session</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form id="getResult" method="POST" action="">
                            @csrf
                            <div class="form-group">
                                <label>Academic Session</label>
                                <select class="form-control select2" name="academicSession" style="width: 100%;">
                                    @foreach ($academicSessions as $academicSession)
                                        <option @if (old('academicSession') == $academicSession) SELECTED @endif>
                                            {{ $academicSession->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academicSession')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{-- /sessional result modal --}}

        {{-- Term result modal --}}
        <div class="modal fade" id="termResultModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Choose Term</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <form id="getResult" method="POST" action="">
                            @csrf
                            <div class="form-group">
                                <label>Academic Session</label>
                                <select class="form-control select2" name="academicSession" style="width: 100%;">
                                    @foreach ($academicSessions as $academicSession)
                                        <option @if (old('academicSession') == $academicSession) SELECTED @endif>
                                            {{ $academicSession->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('academicSession')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="form-group">
                                <label>Term</label>
                                <select class="form-control select2" name="term" style="width: 100%;">
                                    @foreach ($terms as $term)
                                        <option @if (old('term') == $term) SELECTED @endif>
                                            {{ $term->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('term')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror

                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        {{-- /.Term result modal --}}

        {{-- student details modal --}}
        <div class="modal fade" id="viewModal">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Student Details</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-3">

                                    <!-- Profile Image -->
                                    <div class="card card-primary card-outline">
                                        <div class="card-body box-profile">
                                            <div class="text-center">
                                                <img class="profile-user-img img-fluid img-circle"
                                                    src="../../dist/img/user4-128x128.jpg" alt="User profile picture">
                                            </div>

                                            <h3 class="profile-username text-center"></h3>

                                            <p class="text-muted text-center" id="admissionNo"></p>

                                        </div>
                                        <!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->

                                </div>
                                <!-- /.col -->
                                <div class="col-md-9">
                                    <div class="card">
                                        <div class="card-header p-2">
                                            <ul class="nav nav-pills">
                                                <li class="nav-item"><a class="nav-link active" href="#about"
                                                        data-toggle="tab">About</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#guardianInfo"
                                                        data-toggle="tab">Guardian</a></li>
                                                <li class="nav-item"><a class="nav-link" href="#results"
                                                        data-toggle="tab">Results</a></li>
                                            </ul>
                                        </div><!-- /.card-header -->
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="active tab-pane" id="about">
                                                    <strong></i>Class</strong>

                                                    <p class="text-muted" id="classroom">

                                                    </p>

                                                    <hr>

                                                    <strong></i>Local
                                                        government</strong>

                                                    <p class="text-muted" id="lg"></p>

                                                    <hr>

                                                    <strong></i>State</strong>

                                                    <p class="text-muted" id="state"></p>
                                                    <hr>

                                                    <strong></i>Country</strong>

                                                    <p class="text-muted" id="country"></p>
                                                    <hr>

                                                    <strong></i>Date of birth</strong>

                                                    <p class="text-muted" id="dob"></p>

                                                    <hr>

                                                    <strong></i>Place of birth</strong>

                                                    <p class="text-muted" id="pob"></p>

                                                    <hr>
                                                    <strong></i>Blood Group</strong>

                                                    <p class="text-muted" id="bloodGroup"></p>

                                                    <hr>
                                                    <strong></i>status</strong>

                                                    <p class="text-muted" id="status"></p>

                                                </div>
                                                <!-- /.tab-pane -->
                                                <div class="tab-pane" id="guardianInfo">
                                                    <strong></i>Full name</strong>
                                                    <p class="text-muted" id="gFullname"></p>

                                                    <hr>
                                                    <strong></i>Occupation</strong>
                                                    <p class="text-muted" id="gOccupation"></p>

                                                    <hr>
                                                    <strong></i>Email</strong>
                                                    <p class="text-muted" id="gEmail"></p>

                                                    <hr>
                                                    <strong></i>Phone</strong>
                                                    <p class="text-muted" id="gPhone"></p>

                                                    <hr>
                                                    <strong></i>Address</strong>
                                                    <p class="text-muted" id="gAddress"></p>

                                                </div>
                                                <!-- /.tab-pane -->

                                                <div class="tab-pane" id="results">
                                                    <h3>Result Type</h3>
                                                    <div>
                                                        <div class="btn-group">
                                                            <button type="button" id="showSessionalResultButton"
                                                                class="btn btn-info" data-student
                                                                onclick="showSessionalResultModal()">Sessional</button>
                                                            <button type="button" class="btn btn-warning"
                                                                onclick="showTermResultModal()">Term</button>
                                                        </div>
                                                        <span class="ml-3" title="Add new result">
                                                            <button type="button" id="addNewResultButton"
                                                                class="btn btn-success"
                                                                onclick="addNewResult()">+</button>
                                                        </span>
                                                    </div>

                                                </div>
                                                <!-- /.tab-pane -->
                                            </div>
                                            <!-- /.tab-content -->
                                        </div><!-- /.card-body -->
                                    </div>
                                    <!-- /.card -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                        </div>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
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
            // Display edit modal
            function showEditModal(data) {
                let editStudentUrl = '/edit/student/' + data.admission_no
                let editGuardianUrl = '/edit/guardian/' + data.guardian.phone
                $('#editStudentButton').attr("href", editStudentUrl)
                $('#editGuardianButton').attr("href", editGuardianUrl)
                $('#editModal').modal('show')
            }

            function addNewResult() {
                const student = $('#showSessionalResultButton').data('student')

                const createResultUrl = '/create/result/' + student.admission_no
                window.location.href = createResultUrl

            }

            // display sessional result modal
            function showSessionalResultModal() {

                $('#viewModal').modal('hide')

                // get the student data attribute
                const student = $('#showSessionalResultButton').data('student')

                // fill results form
                const ResultUrl = '/results/sessional/student/' + student.admission_no
                $('#sessionalResultModal #getResult').attr("action", ResultUrl)

                $('#sessionalResultModal').modal('show')
            }

            //display term result modal
            function showTermResultModal() {

                $('#viewModal').modal('hide')

                // get the student data attribute
                const student = $('#showSessionalResultButton').data('student')

                // fill results form
                const ResultUrl = '/results/term/student/' + student.admission_no
                $('#termResultModal #getResult').attr("action", ResultUrl)

                $('#termResultModal').modal('show')
            }

            function showViewModal(data, classroom) {
                const fullname = data.first_name + ' ' + data.last_name
                const gFullname = data.guardian.title + ' ' + data.guardian.first_name + ' ' + data.guardian.last_name

                //fill student info in the modal
                $('#viewModal .profile-username').html(fullname)
                $('#viewModal #admissionNo').html(data.admission_no)
                $('#viewModal #classroom').html(classroom.name)
                $('#viewModal #lg').html(data.lg)
                $('#viewModal #state').html(data.state)
                $('#viewModal #dob').html(data.date_of_birth)
                $('#viewModal #pob').html(data.place_of_birth)
                $('#viewModal #bloodGroup').html(data.blood_group)
                $('#viewModal #status').html(data.status)

                //fill guardian info in the modal
                $('#viewModal #gFullname').html(gFullname)
                $('#viewModal #gOccupation').html(data.guardian.occupation)
                $('#viewModal #gEmail').html(data.guardian.email)
                $('#viewModal #gPhone').html(data.guardian.phone)
                $('#viewModal #gAddress').html(data.guardian.address)

                //results info
                //set the data-attribute of the button to contain the student info
                $('#showSessionalResultButton').data('student', data)

                //show modal
                $('#viewModal').modal('show')
            }

            //launch toastr 
            $(function() {
                let Success = document.getElementById('success')
                // if data-success = 'true' display alert
                if (Success.dataset.success == 'true')
                    $(document).Toasts('create', {
                        class: 'bg-success',
                        title: 'Success',
                        subtitle: 'Close',
                        body: JSON.parse(Success.dataset.successMessage)
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
