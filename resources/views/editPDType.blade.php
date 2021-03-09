<x-app-layout>
    <x-slot name="styles">
    </x-slot>
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <span id="success" {{ session('success') ? 'data-success = true' : false }}
            data-success-message='{{ json_encode(session('success')) }}'></span>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Pychomotor Domain Types</h1>
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
                        <!-- Default box -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Edit Pychomotor Domain Type</h3>
                            </div>
                            <form id="" method="POST" action="{{ route('pd-type.update', ['id' => $pdType->id]) }}">
                                @csrf
                                @method('PATCH')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Pychomotor Domain Type">Pychomotor Domain Type</label>
                                        <input type="text" name="name" value="{{ old('name', $pdType->name) }}"
                                            class="form-control @error('name') is-invalid @enderror" id="pdType"
                                            placeholder="Enter Pychomotor Domain Type">
                                        @error('name')
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

    </x-slot>
</x-app-layout>
