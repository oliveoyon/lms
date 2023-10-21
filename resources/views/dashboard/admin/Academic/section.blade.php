@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Section Management')

@push('admincss')
<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')
<style>.modal {
    z-index: 9999;
}
.background-content {
    pointer-events: none;
}

</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Section Management</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Section Management</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-outline">
                        <div class="card-header bg-navy">
                            <h3 class="card-title">
                                <i class="fas fa-chalkboard-teacher mr-1"></i>
                                Section List
                            </h3>
                            <div class="card-tools">
                                <ul class="nav nav-pills ml-auto">
                                    <li class="nav-item">
                                        <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addSectionModal">
                                            <i class="fas fa-plus-square mr-1"></i> Add Section
                                        </button>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-body table-responsive">
                            <div class="alert alert-danger" id="errorAlert" style="display: none;">
                                <ul id="errorList">
                                    <!-- Error messages will be inserted here dynamically -->
                                </ul>
                            </div>
                            <table class="table table-bordered table-striped table-hover table-sm" id="section-table">
                                <thead style="border-top: 1px solid #b4b4b4">
                                    <th style="width: 15px">#</th>
                                    <th>Section Name</th>
                                    <th>Version</th>
                                    <th>Class</th>
                                    <th>Status</th>
                                    <th style="width: 40px">Action</th>
                                </thead>
                                <tbody>
                                    @foreach ($sections as $section)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td class="font-weight-bold">{{ $section->section_name }}</td>
                                        <td>{{ $section->version->version_name }}</td>
                                        <td>{{ $section->eduClass->class_name }}</td>
                                        <td class="{{ $section->section_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                            {{ $section->section_status == 1 ? 'Active' : 'Inactive' }}
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-warning btn-xs" data-id="{{ $section->id }}" id="editSectionBtn">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-xs" data-id="{{ $section->id }}" id="deleteSectionBtn">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>


<div class="modal fade" id="addSectionModal" tabindex="-1" aria-labelledby="addSectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="addSectionModalLabel">Add Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.addSection') }}" method="POST" autocomplete="off" id="add-section-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="section_name">Section Name</label>
                                <input type="text" class="form-control form-control-sm" name="section_name" id="section_name" placeholder="Section Name">
                                <span class="text-danger error-text section_name_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="version_id">Version</label>
                                <select class="form-control form-control-sm version_id" name="version_id" id="version_id">
                                    <option value="">Select a version</option>
                                    @foreach ($versions as $version)
                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="class_id">Class</label>
                                <select class="form-control form-control-sm class_id" name="class_id" id="class_id">
                                    <option value="">Select a class</option>
                                </select>
                                <span class="text-danger error-text class_id_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="class_teacher_id">Class Teacher</label>
                                <select class="form-control form-control-sm" name="class_teacher_id" id="class_teacher_name">
                                    <option value="">Select a class teacher</option>
                                    <option value="1">Teacher 1</option>
                                    <option value="2">Teacher 2</option>
                                    <option value="3">Teacher 3</option>
                                </select>
                                <span class="text-danger error-text class_teacher_id_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="max_students">Max Students</label>
                                <input type="text" class="form-control form-control-sm" name="max_students" id="section_name" placeholder="Max Students">
                                <span class="text-danger error-text max_students_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="section_status">Status</label>
                                <select class="form-control form-control-sm" name="section_status" id="section_status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <span class="text-danger error-text section_status_error"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade editSection" tabindex="-1" role="dialog" aria-labelledby="editSectionLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editSectionLabel">Edit Section</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.updateSectionDetails') }}" method="post" autocomplete="off" id="update-section-form">
                    @csrf
                    <input type="hidden" name="sid">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="section_name">Section Name</label>
                                <input type="text" class="form-control form-control-sm" name="section_name" placeholder="Section Name">
                                <span class="text-danger error-text section_name_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="version_id">Version</label>
                                <select class="form-control form-control-sm version_id" name="version_id">
                                    @foreach($versions as $version)
                                        <option value="{{ $version->id }}">{{ $version->version_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text version_id_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="class_id">Class</label>
                                <select class="form-control form-control-sm class_id" name="class_id">
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->class_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text class_id_error"></span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="class_teacher_id">Class Teacher</label>
                                <select class="form-control form-control-sm" name="class_teacher_id">
                                    <option value="">Select a class teacher</option>
                                    <option value="1">Teacher 1</option>
                                    <option value="2">Teacher 2</option>
                                    <option value="3">Teacher 3</option>
                                </select>
                                <span class="text-danger error-text class_teacher_id_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="max_students">Max Students</label>
                                <input type="text" class="form-control form-control-sm" name="max_students" id="section_name" placeholder="Max Students">
                                <span class="text-danger error-text max_students_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="section_status">Status</label>
                                <select class="form-control form-control-sm" name="section_status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                                <span class="text-danger error-text section_status_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-success">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



</div><!-- /.container-fluid -->
</div>
@endsection

@push('adminjs')
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script>
    $(document).ready(function() {
        // When the "Version" dropdown changes
        $('.version_id').on('change', function() {
            var versionId = $(this).val();
            
            // Make an AJAX request to fetch classes based on the selected version
            $.ajax({
                url: '{{ route('admin.getClassesByVersion') }}',
                method: 'POST',
                data: {
                    version_id: versionId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(data) {
                    var classDropdown = $('.class_id');
                    classDropdown.empty();
                    
                    // Populate the "Class" dropdown with the fetched data
                    $.each(data.classes, function(key, value) {
                        classDropdown.append($('<option>', {
                            value: value.id,
                            text: value.class_name
                        }));
                    });
                }
            });
        });
    });
</script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).ready(function () {
        // Image preview
        $("#upload").change(function () {
            readURL(this);
        });

        $('#add-section-form').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                },
                success: function (data) {
                    if (data.code == 0) {
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        var redirectUrl = data.redirect;
                        $('#addSectionModal').modal('hide');
                        $('#add-section-form')[0].reset();
                        toastr.success(data.msg);

                        setTimeout(function () {
                            window.location.href = redirectUrl;
                        }, 1000);
                    }
                }
            });
        });

        $(document).on('click', '#editSectionBtn', function () {
            var section_id = $(this).data('id');
            $('.editSection').find('form')[0].reset();
            $('.editSection').find('span.error-text').text('');
            $.post("{{ route('admin.getSectionDetails') }}", { section_id: section_id }, function (data) {
                $('.editSection').find('input[name="sid"]').val(data.details.id);
                $('.editSection').find('input[name="section_name"]').val(data.details.section_name);
                $('.editSection').find('input[name="max_students"]').val(data.details.max_students);
                $('.editSection').find('select[name="version_id"]').val(data.details.version_id);
                $('.editSection').find('select[name="class_id"]').val(data.details.class_id);
                $('.editSection').find('select[name="class_teacher_id"]').val(data.details.class_teacher_id);
                $('.editSection').find('select[name="section_status"]').val(data.details.section_status);
                $('.editSection').modal('show');
            }, 'json');
        });

        $('#update-section-form').on('submit', function (e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function () {
                    $(form).find('span.error-text').text('');
                },
                success: function (data) {
                    if (data.code == 0) {
                        $.each(data.error, function (prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        var redirectUrl = data.redirect;
                        $('.editSection').modal('hide');
                        $('.editSection').find('form')[0].reset();
                        toastr.success(data.msg);

                        setTimeout(function () {
                            window.location.href = redirectUrl;
                        }, 1000);
                    }
                }
            });
        });

        // DELETE Section RECORD
        $(document).on('click', '#deleteSectionBtn', function () {
            var section_id = $(this).data('id');
            var url = '<?= route("admin.deleteSection"); ?>';
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this section',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, delete it',
                cancelButtonText: 'Cancel',
            }).then(function (result) {
                if (result.isConfirmed) {
                    $.post(url, { section_id: section_id }, function (data) {
                        if (data.code == 1) {
                            var redirectUrl = data.redirect;
                            toastr.success(data.msg);
                            setTimeout(function () {
                                window.location.href = redirectUrl;
                            }, 1000);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        });
    });
</script>

@endpush