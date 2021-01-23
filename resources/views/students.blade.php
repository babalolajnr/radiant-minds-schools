<x-app-layout>
    <x-slot name="styles">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/toastr/toastr.min.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <span id="success" {{ session('success') ? 'data-success = true' : false }}
            data-success-message='{{ json_encode(session('success')) }}'></span>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Students</h1>
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

                        <!-- Default box -->


                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Students</h3>
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
                                            <th>Class</th>
                                            <th>Guardian</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            $no = 1;
                                        ?>
                                        @foreach($students as $student)
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
                                                {{ $student->classroom->name }}
                                            </td>
                                            <td>
                                                {{ $student->guardian->title. ' '. $student->guardian->first_name. ' '. $student->guardian->last_name }}
                                            </td>
                                            <td>{{ $student->status }}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <button type="button" id="viewStudentButton"
                                                        data-admission-no={{ $student->admission_no }}
                                                        class="btn btn-default btn-flat"
                                                        onclick="showViewModal({{ $student }})" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                    <a href="edit/student/{{ $student->id }}">
                                                        <button type="button" class="btn btn-default btn-flat"
                                                            title="Edit">
                                                            <i class="fa fa-edit"></i>
                                                        </button>
                                                    </a>
                                                    {{-- render if user is authorized to delete --}}
                                                    @can('delete', $student)
                                                    <form action="/delete/student/{{ $student->id }}" method="POST">
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
                                            <th>Class</th>
                                            <th>Guardian</th>
                                            <th>Status</th>
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

                                            <h3 class="profile-username text-center">Nina Mcintire</h3>

                                            <p class="text-muted text-center" id="admissionNo">Software Engineer</p>

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
                                                <li class="nav-item"><a class="nav-link" href="#settings"
                                                        data-toggle="tab">Settings</a></li>
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

                                                    <hr>

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

                                                <div class="tab-pane" id="settings">
                                                    <form class="form-horizontal">
                                                        <div class="form-group row">
                                                            <label for="inputName"
                                                                class="col-sm-2 col-form-label">Name</label>
                                                            <div class="col-sm-10">
                                                                <input type="email" class="form-control" id="inputName"
                                                                    placeholder="Name">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputEmail"
                                                                class="col-sm-2 col-form-label">Email</label>
                                                            <div class="col-sm-10">
                                                                <input type="email" class="form-control" id="inputEmail"
                                                                    placeholder="Email">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputName2"
                                                                class="col-sm-2 col-form-label">Name</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="inputName2"
                                                                    placeholder="Name">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputExperience"
                                                                class="col-sm-2 col-form-label">Experience</label>
                                                            <div class="col-sm-10">
                                                                <textarea class="form-control" id="inputExperience"
                                                                    placeholder="Experience"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <label for="inputSkills"
                                                                class="col-sm-2 col-form-label">Skills</label>
                                                            <div class="col-sm-10">
                                                                <input type="text" class="form-control" id="inputSkills"
                                                                    placeholder="Skills">
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="offset-sm-2 col-sm-10">
                                                                <div class="checkbox">
                                                                    <label>
                                                                        <input type="checkbox"> I agree to the <a
                                                                            href="#">terms and conditions</a>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="offset-sm-2 col-sm-10">
                                                                <button type="submit"
                                                                    class="btn btn-danger">Submit</button>
                                                            </div>
                                                        </div>
                                                    </form>
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
            function showViewModal(data) {
                let fullname = data.first_name + ' ' + data.last_name
                let gFullname = data.guardian.title + ' ' + data.guardian.first_name + ' ' + data.guardian.last_name
                //fill student info in the modal
                $('#viewModal .profile-username').html(fullname)
                $('#viewModal #admissionNo').html(data.admission_no)
                $('#viewModal #classroom').html(data.classroom.name)
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

                //show modal
                $('#viewModal').modal('show')
                console.log(data)
            }
            $(function () {
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
            $(function () {
                $("#example1").DataTable({
                    "responsive": true,
                    "lengthChange": false,
                    "autoWidth": false,
                    "buttons": ["copy", "csv", "excel", "pdf", "print"]
                }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                // $('#example2').DataTable({
                //     "paging": true,
                //     "lengthChange": false,
                //     "searching": false,
                //     "ordering": true,
                //     "info": true,
                //     "autoWidth": false,
                //     "responsive": true,
                // });
            });

        </script>
    </x-slot>
</x-app-layout>
