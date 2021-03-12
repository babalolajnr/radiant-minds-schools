<x-app-layout>
    <x-slot name="styles">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/toastr/toastr.min.css') }}">
        <!-- Select2 -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/select2/css/select2.min.css') }}">
        <link rel="stylesheet"
            href="{{ asset('TAssets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
                        <h1>Results</h1>
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
                    <div class="col-lg-6">
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Result</h3>
                            </div>
                            <form method="POST" action="{{ route('result.update', ['result' => $result]) }}">
                                @csrf
                                @method('PATCH')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Subject">Subject</label>
                                        <select class="form-control select2" style="width: 100%;" disabled>
                                            <option SELECTED>{{ $result->subject->name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Term">Term</label>
                                        <select class="form-control select2" style="width: 100%;" disabled>
                                            <option SELECTED >{{ $result->term->name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="Academic Session">Academic Session</label>
                                        <select class="form-control select2" style="width: 100%;" disabled>
                                            <option SELECTED >{{ $result->academicSession->name }}</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="C.A">C.A</label>
                                        <input type="number" name="ca"
                                            class="form-control @error('ca') is-invalid @enderror"
                                            value="{{ old('ca', $result->ca) }}">
                                        @error('ca')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="Exam">Exam</label>
                                        <input type="number" name="exam"
                                            class="form-control @error('exam') is-invalid @enderror"
                                            value="{{ old('exam', $result->exam) }}">
                                        @error('exam')
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
        <!-- Toastr -->
        <script src="{{ asset('TAssets/plugins/toastr/toastr.min.js') }}"></script>
        <!-- Select2 -->
        <script src="{{ asset('TAssets/plugins/select2/js/select2.full.min.js') }}"></script>
        <!-- AdminLTE App -->
        <script>
            $(function () {
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
            $(function () {

                //Initialize Select2 Elements
                $('.select2').select2()
            })

        </script>
    </x-slot>
</x-app-layout>
