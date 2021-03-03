<x-app-layout>
    <x-slot name="styles">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/toastr/toastr.min.css') }}">
        <!-- DataTables -->
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
                        <h1>{{ $student->name }}</h1>
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">

                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" src="@if ($student->image) {{ asset($student->image) }} @else
                                        {{ asset('images/user1.svg') }} @endif"
                                        alt="student image">
                                    </div>

                                    <h3 class="profile-username text-center">
                                        {{ $student->first_name . ' ' . $student->last_name }}
                                    </h3>

                                    <p class="text-muted text-center" id="admissionNo">{{ $student->admission_no }}
                                    </p>
                                    <div class="d-flex justify-content-center"><button class="btn btn-primary"
                                            data-toggle="modal" data-target="#editModal">Edit</button></div>
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
                                        <li class="nav-item"><a class="nav-link" href="#imageUpload"
                                                data-toggle="tab">Image Upload</a></li>
                                    </ul>

                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="about">
                                            <strong></i>Class</strong>

                                            <p class="text-muted" id="classroom">
                                                {{ $student->classroom->name }}
                                            </p>

                                            <hr>

                                            <strong></i>Local
                                                government</strong>

                                            <p class="text-muted" id="lg"> {{ $student->lg }}</p>

                                            <hr>

                                            <strong></i>State</strong>

                                            <p class="text-muted" id="state"> {{ $student->state }}</p>
                                            <hr>

                                            <strong></i>Country</strong>

                                            <p class="text-muted" id="country">{{ $student->country }}</p>
                                            <hr>

                                            <strong></i>Date of birth</strong>

                                            <p class="text-muted" id="dob">{{ $student->date_of_birth }}</p>

                                            <hr>

                                            <strong></i>Place of birth</strong>

                                            <p class="text-muted" id="pob">{{ $student->place_of_birth }}</p>

                                            <hr>
                                            <strong></i>Blood Group</strong>

                                            <p class="text-muted" id="bloodGroup">{{ $student->blood_group }}</p>

                                            <hr>
                                            <strong></i>status</strong>

                                            <p class="text-muted" id="status">{{ $student->status }}</p>

                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="guardianInfo">
                                            <strong></i>Full name</strong>
                                            <p class="text-muted" id="gFullname">
                                                {{ $student->guardian->title . ' ' . $student->guardian->first_name . ' ' . $student->guardian->last_name }}
                                            </p>

                                            <hr>
                                            <strong></i>Occupation</strong>
                                            <p class="text-muted" id="gOccupation">
                                                {{ $student->guardian->occupation }}</p>

                                            <hr>
                                            <strong></i>Email</strong>
                                            <p class="text-muted" id="gEmail">{{ $student->guardian->email }}</p>

                                            <hr>
                                            <strong></i>Phone</strong>
                                            <p class="text-muted" id="gPhone">{{ $student->guardian->phone }}</p>

                                            <hr>
                                            <strong></i>Address</strong>
                                            <p class="text-muted" id="gAddress">{{ $student->guardian->address }}</p>

                                        </div>
                                        <!-- /.tab-pane -->

                                        <div class="tab-pane" id="results">
                                            <h3>Result Type</h3>
                                            <div>
                                                <div class="btn-group">
                                                    <button type="button" id="showSessionalResultButton"
                                                        class="btn btn-info" data-toggle="modal"
                                                        data-target="#sessionalResultModal">Sessional</button>
                                                    <button type="button" class="btn btn-warning" data-toggle="modal"
                                                        data-target="#sessionalResultModal">Term</button>
                                                </div>
                                                <span class="ml-3" title="Add new result">
                                                    <a href="/create/result/{{ $student->admission_no }}">
                                                        <button type="button" id="addNewResultButton"
                                                            class="btn btn-success">Create Result</button>
                                                    </a>
                                                </span>
                                            </div>

                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="imageUpload">
                                            <form action="/store/image/{{ $student->id }}" method="post"
                                                id="imageUploadForm" enctype="multipart/form-data">
                                                @csrf
                                                <div class="form-group">
                                                    <label for="imageUpload">File input</label>
                                                    <div class="input-group">
                                                        <div class="custom-file">
                                                            <input type="file" name="image" @error('image') is-invalid
                                                                @enderror class="custom-file-input">
                                                            <label class="custom-file-label" for="imageUpload">Choose
                                                                file</label>
                                                        </div>
                                                        <div class="input-group-append">
                                                            <button type="submit">
                                                                <span class="input-group-text">Upload</span></button>
                                                        </div>
                                                    </div>
                                                    @error('image')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </form>
                                        </div>
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
        </section>
        <!-- /.content -->

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
                        <form id="getSessionalResult" method="POST"
                            action="/results/sessional/student/{{ $student->admission_no }}">
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

                        <form id="getTermResult" method="POST"
                            action="/results/term/student/{{ $student->admission_no }}">
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
    </div>

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
                    <a href="/edit/student/{{ $student->admission_no }}" id="editStudentButton">
                        <button type="button" class="btn btn-success" title="Edit Student">Student</button>
                    </a>
                    <span class="px-3"></span>
                    <a href="/edit/guardian/{{ $student->guardian->phone }}" id="editGuardianButton">
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
    <x-slot name="scripts">
        <!-- Toastr -->
        <script src="{{ asset('TAssets/plugins/toastr/toastr.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script>
            
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

        </script>
    </x-slot>
</x-app-layout>
