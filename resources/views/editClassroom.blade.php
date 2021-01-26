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
                        <h1>Classrooms</h1>
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
                                <h3 class="card-title">Edit Classroom <span class="font-semibold">{{ $classroom->name }}</span></h3>
                            </div>
                            <form id="editClassroom" method="POST" action="/update/classroom/{{ $classroom->id }}">
                                @csrf
                                @method('PATCH')
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="Classroom">Classroom</label>
                                        <input type="text" name="name" value="{{ old('name', $classroom->name) }}"
                                            class="form-control @error('name') is-invalid @enderror"
                                            id="classroom" placeholder="Enter Classroom">
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
