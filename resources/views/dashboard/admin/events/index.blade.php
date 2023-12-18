@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Event Management')

@push('admincss')
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush

@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">{{ __('language.event_mgmt') }}</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.home') }}</a></li>
                            <li class="breadcrumb-item active">{{ __('language.event_mgmt') }}</li>
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
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    {{ __('language.event_list') }}
                                </h3>
                                <div class="card-tools">
                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item">
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addEventModal">
                                                <i class="fas fa-plus-square mr-1"></i>
                                                {{ __('language.event_add') }}
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
                    
                                <table class="table table-bordered table-striped table-hover table-sm" id="event-table">
                                    <thead style="border-top: 1px solid #b4b4b4">
                                        <th style="width: 10px">#</th>
                                        <th>{{ __('language.event_title') }}</th>
                                        <th>{{ __('language.event_description') }}</th>
                                        <th>{{ __('language.event_type') }}</th>
                                        <th>{{ __('language.start_date') }}</th>
                                        <th>{{ __('language.end_date') }}</th>
                                        <th>{{ __('language.status') }}</th>
                                        <th style="width: 40px">{{ __('language.action') }}</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($events as $event)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="font-weight-bold">{{ $event->event_title }}</td>
                                                <td>{!! $event->event_description !!}</td>
                                                <td>{{ $event->event_type->type_name }}</td>
                                                <td>{{ $event->start_date }}</td>
                                                <td>{{ $event->end_date }}</td>
                                                <td
                                                    class="{{ $event->event_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                                    {{ $event->event_status == 1 ? __('language.active') : __('language.inactive') }}
                                                </td>
                    
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-warning btn-xs" data-id="{{ $event->id }}"
                                                            id="editEventBtn"><i class="fas fa-edit"></i></button>
                                                        <button type="button" class="btn btn-danger btn-xs" data-id="{{ $event->id }}"
                                                            id="deleteEventBtn"><i class="fas fa-trash-alt"></i></button>
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

              <!-- Add Event Modal -->
<div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title" id="addEventModalLabel">{{ __('language.event_add') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.addEvent') }}" method="POST" enctype="multipart/form-data" autocomplete="off" id="add-event-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="event_title">{{ __('language.event_title') }}</label>
                                <input type="text" class="form-control" name="event_title" id="event_title"
                                    placeholder="{{ __('language.event_title') }}">
                                <span class="text-danger error-text event_title_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">{{ __('language.start_date') }}</label>
                                <input type="datetime-local" class="form-control" name="start_date" id="start_date">
                                <span class="text-danger error-text start_date_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">{{ __('language.end_date') }}</label>
                                <input type="datetime-local" class="form-control" name="end_date" id="end_date">
                                <span class="text-danger error-text end_date_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="event_description">{{ __('language.event_description') }}</label>
                                <textarea class="form-control summernote" name="event_description"
                                    id="event_description" placeholder="{{ __('language.event_description') }}"></textarea>
                                <span class="text-danger error-text event_description_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="event_type_id">{{ __('language.event_type') }}</label>
                                <select class="form-control" name="event_type_id" id="event_type_id">
                                    @foreach ($eventTypes as $eventType)
                                        <option value="{{ $eventType->id }}">{{ $eventType->type_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text event_type_id_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="upload">{{ __('language.upload') }}</label>
                                <input type="file" class="form-control" name="upload" id="upload">
                                <span class="text-danger error-text upload_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="event_status">{{ __('language.status') }}</label>
                                <select class="form-control" name="event_status" id="event_status">
                                    <option value="1">{{ __('language.active') }}</option>
                                    <option value="0">{{ __('language.inactive') }}</option>
                                </select>
                                <span class="text-danger error-text event_status_error"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('language.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- Edit Event Modal -->
<div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title" id="editEventModalLabel">{{ __('language.event_edit') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.updateEventDetails') }}" method="POST" autocomplete="off" id="update-event-form">
                    @csrf
                    <input type="hidden" name="event_id" id="edit_event_id">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="event_title">{{ __('language.event_title') }}</label>
                                <input type="text" class="form-control" name="event_title" id="event_title"
                                    placeholder="{{ __('language.event_title') }}">
                                <span class="text-danger error-text event_title_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="start_date">{{ __('language.start_date') }}</label>
                                <input type="datetime-local" class="form-control" name="start_date" id="start_date">
                                <span class="text-danger error-text start_date_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="end_date">{{ __('language.end_date') }}</label>
                                <input type="datetime-local" class="form-control" name="end_date" id="end_date">
                                <span class="text-danger error-text end_date_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="event_description">{{ __('language.event_description') }}</label>
                                <textarea class="form-control summernote" name="event_description"
                                    id="event_description" placeholder="{{ __('language.event_description') }}"></textarea>
                                <span class="text-danger error-text event_description_error"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="event_type_id">{{ __('language.event_type') }}</label>
                                <select class="form-control" name="event_type_id" id="event_type_id">
                                    @foreach ($eventTypes as $eventType)
                                        <option value="{{ $eventType->id }}">{{ $eventType->type_name }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text event_type_id_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="upload">{{ __('language.upload') }}</label>
                                <input type="file" class="form-control" name="upload" id="upload">
                                <span class="text-danger error-text upload_error"></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="event_status">{{ __('language.status') }}</label>
                                <select class="form-control" name="event_status" id="event_status">
                                    <option value="1">{{ __('language.active') }}</option>
                                    <option value="0">{{ __('language.inactive') }}</option>
                                </select>
                                <span class="text-danger error-text event_status_error"></span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">{{ __('language.save') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
                



            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
@endsection

@push('adminjs')
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.2.0/dist/sweetalert2.min.js"></script>
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(document).ready(function() {
            // Image preview
            $("#upload").change(function() {
                readURL(this);
            });

            $('#add-class-form').on('submit', function(e) {
                e.preventDefault();

                // Disable the submit button to prevent double-clicking
                $(this).find(':submit').prop('disabled', true);

                // Show the loader overlay
                $('#loader-overlay').show();

                var form = this;

                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.code == 0) {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            var redirectUrl = data.redirect;
                            $('#addClassModal').modal('hide');
                            $('#addClassModal').find('form')[0].reset();
                            toastr.success(data.msg);

                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 1000);
                        }
                    },
                    complete: function() {
                        // Enable the submit button and hide the loader overlay
                        $(form).find(':submit').prop('disabled', false);
                        $('#loader-overlay').hide();
                    }
                });
            });


            $(document).on('click', '#editClassBtn', function() {
                var class_id = $(this).data('id');
                $('.editClass').find('form')[0].reset();
                $('.editClass').find('span.error-text').text('');
                $.post("{{ route('admin.getClassDetails') }}", {
                    class_id: class_id
                }, function(data) {
                    $('.editClass').find('input[name="cid"]').val(data.details.id);
                    $('.editClass').find('input[name="class_name"]').val(data.details.class_name);
                    $('.editClass').find('select[name="version_id"]').val(data.details.version_id);
                    $('.editClass').find('input[name="class_numeric"]').val(data.details
                        .class_numeric);
                    $('.editClass').find('select[name="class_status"]').val(data.details
                        .class_status);
                    $('.editClass').modal('show');
                }, 'json');
            });

            // Update Class RECORD
            $('#update-class-form').on('submit', function(e) {
                e.preventDefault();
                var form = this;

                // Disable the submit button to prevent double-clicking
                $(form).find(':submit').prop('disabled', true);

                // Show the loader overlay
                $('#loader-overlay').show();

                $.ajax({
                    url: $(form).attr('action'),
                    method: $(form).attr('method'),
                    data: new FormData(form),
                    processData: false,
                    dataType: 'json',
                    contentType: false,
                    beforeSend: function() {
                        $(form).find('span.error-text').text('');
                    },
                    success: function(data) {
                        if (data.code == 0) {
                            $.each(data.error, function(prefix, val) {
                                $(form).find('span.' + prefix + '_error').text(val[0]);
                            });
                        } else {
                            // $('#category-table').DataTable().ajax.reload(null, false);
                            var redirectUrl = data.redirect;
                            $('.editClass').modal('hide');
                            $('.editClass').find('form')[0].reset();
                            toastr.success(data.msg);

                            setTimeout(function() {
                                window.location.href = redirectUrl;
                            }, 1000); // Adjust the delay as needed (in milliseconds)
                        }
                    },
                    complete: function() {
                        // Enable the submit button and hide the loader overlay
                        $(form).find(':submit').prop('disabled', false);
                        $('#loader-overlay').hide();
                    }
                });
            });

            // DELETE Class RECORD
            $(document).on('click', '#deleteClassBtn', function() {
                var class_id = $(this).data('id');
                var url = '<?= route('admin.deleteClass') ?>';

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You want to delete this class',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    showLoaderOnConfirm: true,
                    preConfirm: function() {
                        // Show the loader overlay
                        $('#loader-overlay').show();

                        return $.post(url, {
                            class_id: class_id
                        }, function(data) {
                            if (data.code == 1) {
                                var redirectUrl = data.redirect;
                                toastr.success(data.msg);
                                setTimeout(function() {
                                    window.location.href = redirectUrl;
                                }, 1000);
                            } else {
                                toastr.error(data.msg);
                            }
                        }, 'json');
                    },
                    allowOutsideClick: function() {
                        // Hide the loader overlay on outside click
                        $('#loader-overlay').hide();
                        return true;
                    }
                });
            });

        });
    </script>

<!-- Initialize Summernote -->
<script>
    $(document).ready(function () {
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'clear']],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['height', ['height']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview']],
            ],
        });


        // Add Event
$('#add-event-form').on('submit', function(e) {
    e.preventDefault();

    // Disable the submit button to prevent double-clicking
    $(this).find(':submit').prop('disabled', true);

    // Show the loader overlay
    $('#loader-overlay').show();

    var form = this;

    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: new FormData(form),
        processData: false,
        dataType: 'json',
        contentType: false,
        beforeSend: function() {
            $(form).find('span.error-text').text('');
        },
        success: function(data) {
            if (data.code == 0) {
                $.each(data.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val[0]);
                });
            } else {
                var redirectUrl = data.redirect;
                $('#addEventModal').modal('hide');
                $('#addEventModal').find('form')[0].reset();
                toastr.success(data.msg);

                setTimeout(function() {
                    window.location.href = redirectUrl;
                }, 1000);
            }
        },
        complete: function() {
            // Enable the submit button and hide the loader overlay
            $(form).find(':submit').prop('disabled', false);
            $('#loader-overlay').hide();
        }
    });
});

// Edit Event
$(document).on('click', '#editEventBtn', function() {
    var event_id = $(this).data('id');
    $('#editEventModal').find('form')[0].reset();
    $('#editEventModal').find('span.error-text').text('');

    $.post("{{ route('admin.getEventDetails') }}", {
        event_id: event_id
    }, function(data) {
        $('#editEventModal').find('input[name="event_id"]').val(data.details.id);
        $('#editEventModal').find('input[name="event_title"]').val(data.details.event_title);
        $('#editEventModal').find('textarea[name="event_description"]').summernote('code', data.details.event_description);
        $('#editEventModal').find('input[name="start_date"]').val(data.details.start_date);
        $('#editEventModal').find('input[name="end_date"]').val(data.details.end_date);
        $('#editEventModal').find('select[name="event_status"]').val(data.details.event_status);


        $('#editEventModal').modal('show');
    }, 'json');
});

// Update Event
$('#update-event-form').on('submit', function(e) {
    e.preventDefault();
    var form = this;

    // Disable the submit button to prevent double-clicking
    $(form).find(':submit').prop('disabled', true);

    // Show the loader overlay
    $('#loader-overlay').show();

    $.ajax({
        url: $(form).attr('action'),
        method: $(form).attr('method'),
        data: new FormData(form),
        processData: false,
        dataType: 'json',
        contentType: false,
        beforeSend: function() {
            $(form).find('span.error-text').text('');
        },
        success: function(data) {
            if (data.code == 0) {
                $.each(data.error, function(prefix, val) {
                    $(form).find('span.' + prefix + '_error').text(val[0]);
                });
            } else {
                var redirectUrl = data.redirect;
                $('#editEventModal').modal('hide');
                $('#editEventModal').find('form')[0].reset();
                toastr.success(data.msg);

                setTimeout(function() {
                    window.location.href = redirectUrl;
                }, 1000);
            }
        },
        complete: function() {
            // Enable the submit button and hide the loader overlay
            $(form).find(':submit').prop('disabled', false);
            $('#loader-overlay').hide();
        }
    });
});

// Delete Event
$(document).on('click', '#deleteEventBtn', function() {
    var event_id = $(this).data('id');
    var url = '<?= route('admin.deleteEvent') ?>';

    Swal.fire({
        title: 'Are you sure?',
        text: 'You want to delete this event',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it',
        cancelButtonText: 'Cancel',
        showLoaderOnConfirm: true,
        preConfirm: function() {
            // Show the loader overlay
            $('#loader-overlay').show();

            return $.post(url, {
                event_id: event_id
            }, function(data) {
                if (data.code == 1) {
                    var redirectUrl = data.redirect;
                    toastr.success(data.msg);
                    setTimeout(function() {
                        window.location.href = redirectUrl;
                    }, 1000);
                } else {
                    toastr.error(data.msg);
                }
            }, 'json');
        },
        allowOutsideClick: function() {
            // Hide the loader overlay on outside click
            $('#loader-overlay').hide();
            return true;
        }
    });
});

    });

</script>
@endpush
