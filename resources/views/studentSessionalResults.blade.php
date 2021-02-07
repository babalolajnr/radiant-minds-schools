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
                        <h1>Results</h1>
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
                                <h3 class="card-title">Results</h3>
                            </div>
                            <div class="card-body">
                                @foreach($results as $termResult)


                                {{-- The table id is gotten by first getting the associative array index then using it to get the numeric index --}}
                                <table
                                    id="{{ array_search(array_search($termResult, $results), array_keys($results)) }}"
                                    class="table table-bordered table-striped">
                                    <thead>
                                        <h3>{{ array_search($termResult, $results) }}</h3>
                                        <tr>
                                            <th>Subject</th>
                                            <th>C.A.<span class="text-red-500 pl-1">40</span></th>
                                            <th>Exam<span class="text-red-500 pl-1">60</span></th>
                                            <th>Total<span class="text-red-500 pl-1">100</span></th>
                                            <th>Highest Score</th>
                                            <th>Lowest Score</th>
                                            <th>Class Average</th>
                                            <th>Grade</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        @foreach($termResult as $item)
                                        <tr>
                                            <td>{{ $item->subject->name }}</td>
                                            <td>{{ $item->ca }}</td>
                                            <td>{{ $item->exam }}</td>
                                            <td>{{ $item->total }}</td>
                                            <td>{{ $maxScores[$item->subject->name.'-'.array_search($termResult, $results)] }}
                                            <td>{{ $minScores[$item->subject->name.'-'.array_search($termResult, $results)] }}</td>
                                            <td>{{ $averageScores[$item->subject->name.'-'.array_search($termResult, $results)] }}</td>
                                            
                                            @if($item->total <= 39)
                                                <td class="text-red-700">F</td>
                                            @elseif($item->total >= 40 && $item->total <= 49)
                                                <td class="text-yellow-500">D</td>
                                            @elseif($item->total >= 50 && $item->total <= 59)
                                                <td class="text-green-300">C</td>
                                            @elseif($item->total >= 60 && $item->total <= 69)
                                                <td class="text-green-600">B</td>
                                            @elseif($item->total >= 70 && $item->total <= 100)
                                                <td class="text-green-900">A</td>
                                            @else
                                                <td></td>
                                            @endif
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
                                        </tr>
                                    </tfoot>
                                </table>
                                <hr class="py-4">
                                @endforeach
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
                // looping through all the tables to assign dynamic numeric id to the datatables initialization
                $('table').each(function () {

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
