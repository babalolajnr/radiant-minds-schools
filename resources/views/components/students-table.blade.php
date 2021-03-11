<div>
    <!-- The only way to do great work is to love what you do. - Steve Jobs -->
    <table id="example1" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>S/N</th>
                <th>Admission No</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Sex</th>
                <th>Guardian</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            @foreach ($students as $student)
                <tr>
                    <td>
                        <?php
                        echo $no;
                        $no++;
                        ?>
                    </td>
                    <td>{{ $student->admission_no }}</td>
                    <td>
                        {{ $student->first_name }}
                    </td>
                    <td>
                        {{ $student->last_name }}
                    </td>
                    <td>
                        {{ $student->sex }}
                    </td>
                    <td>
                        {{ $student->guardian->title . ' ' . $student->guardian->first_name . ' ' . $student->guardian->last_name }}
                    </td>
                    <td>{{ $student->status }}</td>
                    <td>
                        <div class="btn-group">
                            <a href="{{ route('student.show', ['student' => $student]) }}">
                                <button type="button" id="" class="btn btn-default btn-flat"
                                    title="Student detailed view">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </a>

                            {{-- render if user is authorized to delete --}}
                            @can('delete', $student)
                                <button type="submit" class="btn btn-default btn-flat" title="Delete"
                                    onclick="deleteConfirmationModal('{{ route('student.destroy', ['student' => $student]) }}', {{ $student }})">
                                    <i class="fas fa-trash"></i>
                                </button>
                            @endcan

                            {{-- render if user is not authorized to delete --}}
                            @cannot('delete', $student)
                            <button type="submit" class="btn btn-default btn-flat" title="Delete" disabled>
                                <i class="fas fa-trash"></i>
                            </button>
                            @endcannot

                            <a
                                href="{{ route('student.show.student.settingsView', ['student' => $student]) }}">
                                <button type="button" class="btn btn-default btn-flat">
                                    <i class="fas fa-cogs"></i>
                                </button>
                            </a>

                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>S/N</th>
                <th>Admission No</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Sex</th>
                <th>Guardian</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </tfoot>
    </table>
</div>
