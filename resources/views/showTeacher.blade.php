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
                        <h1>{{ $teacher->name }}</h1>
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">

                            <!-- Profile Image -->
                            <div class="card card-primary card-outline">
                                <div class="card-body box-profile">
                                    <div class="text-center">
                                    <img class="profile-user-img img-fluid img-circle" src="@if ($teacher->image) {{ asset($teacher->image) }} @else
                                        {{ asset('images/user1.svg') }} @endif"
                                        alt="teacher image">
                                    </div>

                                    <h3 class="profile-username text-center">
                                        {{ $teacher->first_name . ' ' . $teacher->last_name }}
                                    </h3>

                                    <p class="text-muted text-center" id="admissionNo">{{ $teacher->admission_no }}
                                    </p>
                                    <div class="d-flex justify-content-center">
                                        <a href="{{ route('teacher.edit', ['teacher' => $teacher]) }}">
                                            <button class="btn btn-primary" data-toggle="modal"
                                                data-target="#editModal">Edit</button>
                                        </a>
                                    </div>
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
                                        <li class="nav-item"><a class="nav-link" href="#status"
                                                data-toggle="tab">Status</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#imageUpload"
                                                data-toggle="tab">Image Upload</a></li>
                                    </ul>

                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="about">
                                            <strong></i>Class</strong>

                                            @if ($teacher->classroom)
                                                <a
                                                    href="{{ route('classroom.show', ['classroom' => $teacher->classroom]) }}">
                                                    <p class="text-info" id="classroom">
                                                        {{ $teacher->classroom->name }}
                                                    </p>
                                                </a>
                                            @endif

                                            <hr>

                                            <strong></i>Date of birth</strong>

                                            <p class="text-muted" id="dob">{{ $teacher->date_of_birth }}</p>

                                        </div>

                                        <!-- /.tab-pane -->

                                        <div class="tab-pane" id="status">
                                            <div class="form-group row">
                                                {{-- Status --}}
                                                <label for="status" class="col-sm-2 col-form-label">Status</label>
                                                <div class="col-sm-10">
                                                    <div class="btn-group">
                                                        <form
                                                            action="{{ route('teacher.activate', ['teacher' => $teacher]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn @if ($teacher->isActive()) btn-primary
                                                            disabled @else btn-default @endif
                                                                btn-flat"
                                                                @if ($teacher->isActive()) disabled
                                                                @endif>
                                                                Activate
                                                            </button>
                                                        </form>
                                                        <form
                                                            action="{{ route('teacher.deactivate', ['teacher' => $teacher]) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn @if (!$teacher->isActive()) btn-primary
                                                            disabled @else btn-default @endif
                                                                btn-flat"
                                                                @if (!$teacher->isActive()) disabled
                                                                @endif>
                                                                Deactivate
                                                            </button>
                                                        </form>

                                                    </div>
                                                </div>
                                                {{-- /Status --}}
                                            </div>
                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="imageUpload">
                                            <form action="" method="post" id="imageUploadForm"
                                                enctype="multipart/form-data">
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

    </div>

    {{-- /edit teacher modal --}}
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
