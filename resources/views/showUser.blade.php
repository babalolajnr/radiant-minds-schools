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
                        <h1>User</h1>
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
                                        <img class="profile-user-img img-fluid img-circle"
                                            src="{{ asset('images/user1.svg') }}" alt="user image">
                                    </div>

                                    <h3 class="profile-username text-center">
                                        {{ $user->first_name . ' ' . $user->last_name }}
                                    </h3>

                                    <p class="text-muted text-center" id="admissionNo">{{ $user->user_type }}
                                    </p>
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
                                        <li class="nav-item"><a class="nav-link" href="#edit" data-toggle="tab">Edit</a>
                                        </li>
                                        <li class="nav-item"><a class="nav-link" href="#changePassword"
                                                data-toggle="tab">Change Password</a></li>
                                    </ul>

                                </div><!-- /.card-header -->
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="active tab-pane" id="about">
                                            <strong></i>User Type</strong>
                                            <p class="text-muted" id="user_type">{{ $user->user_type }}</p>

                                            <hr>

                                            <strong></i>Email</strong>
                                            <p class="text-muted" id="user_email">{{ $user->email }}</p>

                                            <hr>

                                            <strong></i>Verified</strong>
                                            <p class="text-muted" id="verified">
                                                @if ($user->isVerified())
                                                    Yes
                                                @else
                                                    No
                                                @endif
                                            </p>

                                            <hr>

                                            <strong></i>Date joined</strong>
                                            <p class="text-muted" id="created_at">{{ $user->created_at }}</p>

                                        </div>
                                        <!-- /.tab-pane -->
                                        <div class="tab-pane" id="edit">
                                            <form>
                                                <div class="form-group">
                                                    <label for="first_name">First name</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter first name" name="first_name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="last_name">Last name</label>
                                                    <input type="text" class="form-control"
                                                        placeholder="Enter last name" name="last_name" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" name="email"
                                                        placeholder="Enter Email" required>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- /.tab-pane -->

                                        <div class="tab-pane" id="changePassword">
                                            <form>
                                                <div class="form-group">
                                                    <label for="old_password">Current password</label>
                                                    <input type="password" class="form-control"
                                                        placeholder="Enter current password" name="current_password"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="new_password">New password</label>
                                                    <input type="password" class="form-control"
                                                        placeholder="Enter new password" name="new_password" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="confirm_password">Cofirm password</label>
                                                    <input type="password" class="form-control"
                                                        placeholder="Confirm new password" name="confirm_password"
                                                        required>
                                                </div>
                                                <div class="form-group">
                                                    <button type="submit" class="btn btn-primary">Submit</button>
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
