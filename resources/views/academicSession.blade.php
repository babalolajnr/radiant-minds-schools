<x-app-layout>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
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
                                <h3 class="card-title">New Academic Session</h3>
                            </div>
                            <form id="addAcademicSession">
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Academic Session">Academic Session</label>
                                        <input type="text" name="academicSession" class="form-control" id="academicSession" placeholder="Enter Academic Session">
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
        <!-- jquery-validation -->
        <script src="{{ asset('TAssets/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
        <script src="{{ asset('TAssets/plugins/jquery-validation/additional-methods.min.js')}}"></script>
        <script>
            $(function() {
                $.validator.setDefaults({
                    submitHandler: function() {
                        alert("Form successful submitted!");
                    }
                });
                $('#addAcademicSession').validate({
                    rules: {
                        academicSession: {
                            required: true,
                        },

                    },
                    messages: {
                        academicSession: {
                            required: "Please enter an academic session",
                        },
                    },
                    errorElement: 'span',
                    errorPlacement: function(error, element) {
                        error.addClass('invalid-feedback');
                        element.closest('.form-group').append(error);
                    },
                    highlight: function(element, errorClass, validClass) {
                        $(element).addClass('is-invalid');
                    },
                    unhighlight: function(element, errorClass, validClass) {
                        $(element).removeClass('is-invalid');
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>