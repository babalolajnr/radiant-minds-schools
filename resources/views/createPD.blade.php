<x-app-layout>
    <x-slot name="styles">
        <!-- Toastr -->
        <link rel="stylesheet" href="{{ asset('TAssets/plugins/toastr/toastr.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/range-slider.min.css') }}">
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
                        <h1>Pyschomotor Domain Form</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{ url()->previous() }}">Back</a></li>
                            </ol>
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
                        {{-- Teacher --}}
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">{{ $student->first_name . ' ' . $student->last_name }}</h3>
                            </div>
                            {{-- PD --}}

                            <form action="@if (!$period->isActive()) {{ route('pd.storeOrUpdate', ['student' => $student, 'periodSlug' => $period->id]) }}
                            @else {{ route('pd.storeOrUpdate', ['student' => $student]) }} @endif"
                                method="POST">
                                @csrf
                                <div class="card-body">
                                    <!-- radio -->
                                    @if (!is_null($pdTypesValues))
                                        {{-- if the fields already have values they should be automaically filled --}}
                                        @foreach ($pdTypes as $pdType)
                                            <div class="form-group">
                                                <label for="customRange1">{{ $pdType->name }}<span
                                                        class="font-light pl-4">range(1-5)</span></label>
                                                <input type="range" class="custom-range" min="1" max="5"
                                                    name="pdTypes[{{ $pdType->slug }}]" id="{{ $pdType->slug }}"
                                                    @if (array_key_exists($pdType->id, $pdTypesValues)) value="{{ $pdTypesValues[$pdType->id] }}" @endif>
                                            </div>
                                        @endforeach
                                    @else
                                        {{-- if the fields have not been previously filled --}}
                                        @foreach ($pdTypes as $pdType)
                                            <div class="form-group">
                                                <label for="customRange1">{{ $pdType->name }}<span
                                                        class="font-light pl-4">range(1-5)</span></label>
                                                <input type="range" class="custom-range" min="1" max="5"
                                                    name="pdTypes[{{ $pdType->slug }}]" id="{{ $pdType->slug }}">
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="card-footer">
                                    <div class="form-group">
                                        <button class="btn btn-primary" type="submit">Submit</button>
                                    </div>
                                </div>
                            </form>
                            {{-- /PD --}}
                        </div>

                    </div>
                </div>
        </section>
        <!-- /.content -->

    </div>

    <x-slot name="scripts">
        <!-- Toastr -->
        <script src="{{ asset('TAssets/plugins/toastr/toastr.min.js') }}"></script>
        <script src="{{ asset('js/range-slider.min.js') }}">
        </script>
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
