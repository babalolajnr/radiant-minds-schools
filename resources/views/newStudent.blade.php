<x-app-layout>
    <x-slot name="styles">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css"') }}">
    </x-slot>
    <div class=" content-wrapper">
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
                                <h3 class="card-title">New Student</h3>
                            </div>
                            <form id="addNewStudent" method="POST" action="/store/student">
                                @csrf
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>First name</label>
                                        <input type=" text" name="first_name"
                                            class="form-control @error('first_name') is-invalid @enderror" value="{{ old('first_name') }}">
                                        @error('first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Last name</label>
                                        <input type="text" name="last_name"
                                            class="form-control @error('last_name') is-invalid @enderror" value="{{ old('last_name') }}">
                                        @error('last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Sex</label>
                                        <select class="custom-select @error('sex') is-invalid @enderror" name="sex">
                                            <option>M</option>
                                            <option>F</option>
                                        </select>
                                        @error('sex')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Admission Number</label>
                                        <input type=" text" name="admission_no"
                                            class="form-control @error('admission_no') is-invalid @enderror" value="{{ old('admission_no') }}">
                                        @error('admission_no')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Local government</label>
                                        <input type=" text" name="lg"
                                            class="form-control @error('lg') is-invalid @enderror" value="{{ old('lg') }}">
                                        @error('lg')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>State</label>
                                        <input type=" text" name="state"
                                            class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}">
                                        @error('state')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Country</label>
                                        <input type=" text" name="country"
                                            class="form-control @error('country') is-invalid @enderror" value="{{ old('country') }}">
                                        @error('country')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Blood group</label>
                                        <select class="form-control select2" name="blood_group" style="width: 100%;">
                                            <option selected="selected">Alabama</option>
                                            <option>Alaska</option>
                                            <option>California</option>
                                            <option>Delaware</option>
                                            <option>Tennessee</option>
                                            <option>Texas</option>
                                            <option>Washington</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>Date of birth</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i
                                                        class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="text"
                                                class="form-control @error('date_of_birth') is-invalid @enderror"
                                                data-inputmask-alias="datetime" name="date_of_birth"
                                                data-inputmask-inputformat="yyyy-mm-dd" data-mask value="{{ old('date_of_birth') }}">
                                            @error('date_of_birth')
                                            <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <!-- /.input group -->
                                    </div>
                                    <div class="form-group">
                                        <label>Place of birth</label>
                                        <input type="text" name="place_of_birth"
                                            class="form-control @error('place_of_birth') is-invalid @enderror" value="{{ old('place_of_birth') }}">
                                        @error('place_of_birth')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Class</label>
                                        <select class="form-control select2" name="classroom" style="width: 100%;">
                                            @foreach ($classrooms as $classroom)
                                            <option>{{ $classroom }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <hr class="mt-5">

                                    <h3>Guardian Information</h3>

                                    <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" name="guardian_title"
                                            class="form-control @error('guardian_title') is-invalid @enderror" value="{{ old('guardian_title') }}">
                                        @error('guardian_title')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>First name</label>
                                        <input type=" text" name="guardian_first_name"
                                            class="form-control @error('guardian_first_name') is-invalid @enderror" value="{{ old('guardian_first_name') }}">
                                        @error('guardian_first_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Last name</label>
                                        <input type=" text" name="guardian_last_name"
                                            class="form-control @error('guardian_last_name') is-invalid @enderror" value="{{ old('guardian_last_name') }}">
                                        @error('guardian_last_name')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label>Email address</label>
                                        <input type="email" name="guardian_email"
                                            class="form-control @error('guardian_email') is-invalid @enderror" value="{{ old('guardian_email') }}">
                                        @error('guardian_email')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Phone number</label>
                                        <input type=" text" name="guardian_phone"
                                            class="form-control @error('guardian_phone') is-invalid @enderror" value="{{ old('guardian_phone') }}">
                                        @error('guardian_phone')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Occupation</label>
                                        <input type=" text" name="guardian_occupation"
                                            class="form-control @error('guardian_occupation') is-invalid @enderror" value="{{ old('guardian_occupation') }}">
                                        @error('guardian_occupation')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type=" text" name="guardian_address"
                                            class="form-control @error('guardian_address') is-invalid @enderror" value="{{ old('guardian_address') }}">
                                        @error('guardian_address')
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
        <!-- Select2 -->
        <script src="{{ asset('TAssets/plugins/select2/js/select2.full.min.js') }}"></script>
        <!-- InputMask -->
        <script src="{{ asset('TAssets/plugins/moment/moment.min.js') }}"></script>
        <script src="{{ asset('TAssets/plugins/inputmask/jquery.inputmask.min.js') }}"></script>
        <script>
            $(function () {
                //Initialize Select2 Elements
                $('.select2').select2()
                $('#datemask').inputmask('yyyy-mm-dd', {
                    'placeholder': 'yyyy-mm-dd'
                })
                //Money Euro
                $('[data-mask]').inputmask()

            })

        </script>
    </x-slot>
</x-app-layout>
