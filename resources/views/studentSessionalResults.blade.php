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
                        <h1>Results {{ $academicSession->name }}</h1>
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
                        @if (!empty($results))

                            @foreach ($results as $key => $termResult)
                                <!-- Default box -->
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="card-title text-bold">{{ $key }}</h2>
                                    </div>
                                    <div class="card-body">
                                        {{-- The table id is gotten by first getting the associative array index then using it to get the numeric index --}}
                                        <table id="{{ str_replace(' ', '-', $key) }}"
                                            class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>C.A.<span class="text-red-500 pl-1">40</span></th>
                                                    <th>Exam<span class="text-red-500 pl-1">60</span></th>
                                                    <th>Total<span class="text-red-500 pl-1">100</span></th>
                                                    <th>Highest Score</th>
                                                    <th>Lowest Score</th>
                                                    <th>Class Average</th>
                                                    <th>Grade</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($termResult as $itemKey => $item)
                                                    <tr>
                                                        <td>{{ $item->subject->name }}</td>
                                                        <td>{{ $item->ca }}</td>
                                                        <td>{{ $item->exam }}</td>
                                                        <td>{{ $item->total }}</td>
                                                        <td>{{ $maxScores[$item->subject->name . '-' . array_search($termResult, $results)] }}
                                                        <td>{{ $minScores[$item->subject->name . '-' . array_search($termResult, $results)] }}
                                                        </td>
                                                        <td>{{ round($averageScores[$item->subject->name . '-' . array_search($termResult, $results)], 2) }}
                                                        </td>

                                                        @if ($item->total <= 39)
                                                            <td>F</td>
                                                        @elseif($item->total > 39 && $item->total <= 49) <td>D</td>
                                                            @elseif($item->total > 49 && $item->total <= 59) <td>C
                                                                    </td>
                                                                @elseif($item->total > 59 && $item->total <= 69)
                                                                        <td>B</td>
                                                                    @elseif($item->total > 69 && $item->total <=
                                                                            100) <td>A</td>
                                                                        @else
                                                                            <td></td>
                                                        @endif
                                                        <td>
                                                            <div class="btn-group">
                                                                <a
                                                                    href="{{ route('result.edit', ['result' => $item->id]) }}">
                                                                    <button type="button" id=""
                                                                        class="btn btn-default btn-flat" title="Edit">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>
                                                                </a>
                                                                <button type="submit" class="btn btn-default btn-flat"
                                                                    title="Delete"
                                                                    onclick="deleteConfirmationModal('{{ route('result.destroy', ['result' => $item->id]) }}', '{{ $item->subject->name }}')">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Subject</th>
                                                    <th>C.A.<span class="text-red-500 pl-1">40</span></th>
                                                    <th>Exam<span class="text-red-500 pl-1">60</span></th>
                                                    <th>Total<span class="text-red-500 pl-1">100</span></th>
                                                    <th>Highest Score</th>
                                                    <th>Lowest Score</th>
                                                    <th>Class Average</th>
                                                    <th>Grade</th>
                                                    <th>Action</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                        @else
                            No results for this academic session 😢
                        @endif
                    </div>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    {{-- Delete confirmation modal --}}
    <div class="modal fade" id="deleteConfirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete <span id="deleteItemName" class="font-bold"></span> result?
                </div>
                <div class="modal-footer justify-content-between">
                    <form action="" method="POST" id="yesDeleteConfirmation">
                        @method('DELETE')
                        @csrf
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
            function deleteConfirmationModal(url, name) {
                $('#yesDeleteConfirmation').attr("action", url)
                $('#deleteItemName').html(name)
                $('#deleteConfirmationModal').modal('show')
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
                // looping through all the tables to assign dynamic numeric id to the datatables initialization
                $('table').each(function() {

                    const tableID = $(this).attr('id')
                    
                    $("#" + tableID).DataTable({
                        "responsive": true,
                        "lengthChange": false,
                        "autoWidth": false,
                        "buttons": ["copy", "csv", "excel", "pdf", "print"]
                    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
                })
            });

        </script>
    </x-slot>
</x-app-layout>
