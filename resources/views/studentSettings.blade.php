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
                        <h1>Student Settings</h1>
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
                        {{-- Teacher --}}
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $student->first_name . ' ' . $student->last_name }}</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-horizontal">
                                    <div class="form-group row">
                                        {{-- Status --}}
                                        <label for="status" class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <div class="btn-group">
                                                <form
                                                    action="{{ route('student.activate', ['student' => $student]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn @if ($student->status == 'active') btn-primary
                                                    disabled @else btn-default @endif btn-flat"
                                                        @if ($student->status == 'active') disabled
                                                        @endif>
                                                        Activate
                                                    </button>
                                                </form>
                                                <form action="{{ route('student.suspend', ['student' => $student]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn @if ($student->status == 'suspended') btn-primary
                                                    disabled @else btn-default @endif btn-flat"
                                                        @if ($student->status == 'suspended') disabled
                                                        @endif>
                                                        Suspend
                                                    </button>
                                                </form>
                                                <form
                                                    action="{{ route('student.deactivate', ['student' => $student]) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn @if ($student->status == 'inactive') btn-primary
                                                    disabled @else btn-default @endif btn-flat"
                                                        @if ($student->status == 'inactive') disabled
                                                        @endif>
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
                                                <button type="button"
                                                    onclick="changeClassConfirmationModal('{{ route('student.promote', ['student' => $student]) }}', {{ $student }}, 'promote')"
                                                    class="btn btn-success btn-flat">
                                                    Promote
                                                </button>

                                                <button type="button"
                                                    onclick="changeClassConfirmationModal('{{ route('student.demote', ['student' => $student]) }}',{{ $student }}, 'demote')"
                                                    class="btn btn-danger btn-flat">
                                                    Demote
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- /class --}}

                                    {{-- PD --}}
                                    <div class="form-group row">
                                        <label for="class" class="col-sm-2 col-form-label">Psychomotor
                                            Domain({{ $currentAcademicSession->name }})</label>
                                        <div class="col-sm-6">
                                            <button type="button" onclick="showChooseTermModalPD()"
                                                class="btn btn-info btn-flat">
                                                Create/Update Psychomotor domains
                                            </button>
                                        </div>
                                    </div>
                                    {{-- /PD --}}

                                    {{-- AD --}}
                                    <div class="form-group row">
                                        <label for="class" class="col-sm-2 col-form-label">Affective
                                            Domain({{ $currentAcademicSession->name }})</label>
                                        <div class="col-sm-6">
                                            <button type="button" onclick="showChooseTermModalAD()"
                                                class="btn btn-success btn-flat">
                                                Create/Update Affective domains
                                            </button>
                                        </div>
                                    </div>
                                    {{-- /AD --}}
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
        </section>
        <!-- /.content -->

    </div>
    
    {{-- choose term modal for PD --}}
    <div class="modal fade" id="chooseTermPD">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Choose Term</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($terms as $term)
                        <a href="{{ route('pd.create', ['student' => $student, 'termSlug' => $term->slug]) }}">
                            <button type="button" class="btn btn-primary">{{ $term->name }}</button>
                        </a>
                    @endforeach
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- choose term modal for AD --}}
    <div class="modal fade" id="chooseTermAD">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Choose Term</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @foreach ($terms as $term)
                        <a href="{{ route('ad.create', ['student' => $student, 'termSlug' => $term->slug]) }}">
                            <button type="button" class="btn btn-primary">{{ $term->name }}</button>
                        </a>
                    @endforeach
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    {{-- change class confirmation modal --}}
    <div class="modal fade" id="changeClassConfirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to <span id="confirmationModalStudentName" class="font-bold"></span>?
                </div>
                <div class="modal-footer justify-content-between">
                    <form action="" id="changeClassForm" method="post">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="btn btn-danger">Yes</button>
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
            function changeClassConfirmationModal(url, data, action) {

                const studentName = data.first_name + ' ' + data.last_name

                $('#changeClassForm').attr("action", url)

                if (action == 'promote') {
                    $('#confirmationModalStudentName').html(action + ' ' + studentName)
                } else if (action == 'demote') {
                    $('#confirmationModalStudentName').html(action + ' ' + studentName)
                }

                $('#changeClassConfirmationModal').modal('show')
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

            // displays term modal for PD
            function showChooseTermModalPD() {
                $('#chooseTermPD').modal('show')
            }

            // displays term modal for AD
            function showChooseTermModalAD() {
                $('#chooseTermAD').modal('show')
            }

        </script>
    </x-slot>
</x-app-layout>
