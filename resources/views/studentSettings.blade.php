<x-app-layout>
    <x-slot name="styles">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/toastr/toastr.min.css') }}">
        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
    </x-slot>

    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <span id="success" {{ session('success') ? 'data-success = true' : false }}
            data-success-message='{{ json_encode(session('success')) }}'></span>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Student Settings</h1>
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
                                <h3 class="card-title">{{ $student->first_name.' '.$student->last_name }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        {{-- Status --}}
                                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <div class="btn-group">
                                                <form action="/activate/student/{{ $student->id }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn @if ($student->status == 'active') btn-primary disabled @else btn-default @endif btn-flat"
                                                        @if ($student->status == 'active') disabled @endif>
                                                        Activate
                                                    </button>
                                                </form>
                                                <form action="/suspend/student/{{ $student->id }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn @if ($student->status == 'suspended') btn-primary disabled @else btn-default @endif btn-flat"
                                                        @if ($student->status == 'suspended') disabled @endif>
                                                        Suspend
                                                    </button>
                                                </form>
                                                <form action="/deactivate/student/{{ $student->id }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit"
                                                        class="btn @if ($student->status == 'inactive') btn-primary disabled @else btn-default @endif btn-flat"
                                                        @if ($student->status == 'inactive') disabled @endif>
                                                        Deactivate
                                                    </button>
                                                </form>

                                            </div>
                                        </div>
                                        {{-- /Status --}}
                                    </div>
                                    {{-- class --}}
                                    <div class="form-group row">
                                        <label for="class" class="col-sm-2 col-form-label">Class</label>
                                        <div class="col-sm-10">
                                            <div class="btn-group">
                                                <form action="/promote/student/{{ $student->id }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-success btn-flat">
                                                        Promote
                                                    </button>
                                                </form>
                                                <form action="/demote/student/{{ $student->id }}" method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-danger btn-flat">
                                                        Demote
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /class --}}
                                </div>
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

            //datatables
            $(function () {
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
