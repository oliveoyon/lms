@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title',  'Version')
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
            <h1 class="m-0">Version</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Version</a></li>
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
                          Version List
                        </h3>
                        <div class="card-tools">
                          <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                
                              <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addversions"><i class="fas fa-plus-square mr-1"></i> Add Version</button>
                            </li>
                          </ul>
                        </div>
                      </div>
                      <div class="card-body table-responsive">
                          <table class="table table-bordered table-striped table-hover table-sm" id="datas-table">
                              <thead style="border-top: 1px solid #b4b4b4">
                                  <th style="width: 10px">#</th>
                                  <th>Version Name</th>
                                  <th>Status</th>
                                  <th style="width: 40px">Action <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">{{ __('language.deleteall') }}</button></th>
                              </thead>
                              <tbody>
                                @foreach ($versions as $version)
                                <tr>
                                  <td>{{ $loop->iteration }}</td>
                                  <td class="font-weight-bold">{{ $version->version_name }}</td>
                                  <td class="{{ $version->version_status == 1 ? 'text-success' : 'text-danger' }} font-weight-bold">
                                    {{ $version->version_status == 1 ? 'Active' : 'InActive' }}
                                  </td>
                                  
                                  <td>
                                    <div class="btn-group">
                                      <button type="button" class="btn btn-warning btn-xs" data-id="{{ $version->id }}" id="editVersionBtn"><i class="fas fa-edit"></i></button>
                                      <button type="button" class="btn btn-danger btn-xs" data-id="{{ $version->id }}" id="deleteVersionBtn"><i class="fas fa-trash-alt "></i></button>
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


<!--Add Menu Modal -->
  <div class="modal fade" id="addversions" tabindex="-1" aria-labelledby="addVersionLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-success">
          <h5 class="modal-title" id="addVersionLabel">Add Version</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{ route('admin.addVersion') }}" enctype="multipart/form-data" files="true" method="post" autocomplete="off" id="add-version-form">
            @csrf
        
            <div class="form-group">
                <label for="version_name">Version Name</label>
                <input type="text" class="form-control" name="version_name" id="version_name" placeholder="Version Name লিখুন">
                <span class="text-danger error-text version_name_error"></span>
            </div>
        
            <div class="form-group">
              <label for="status">Status</label>
              <select class="form-control" name="version_status" id="version_status">
                  <option value="1">Active</option>
                  <option value="0">Inactive</option>
              </select>
              <span class="text-danger error-text version_status_error"></span>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-block btn-success">Submit</button>
            </div>
        </form>

        
        </div>
        
      </div>
    </div>
  </div>
  {{-- Modal End --}}

  
{{-- Edit Modal --}}
  <div class="modal fade editVersion" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">মেনু সংশোধন করুন</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            {{-- {{ route('admin.updatecategoryDetails'); }} --}}
            <div class="modal-body">
              <form action="{{ route('admin.updateVersionDetails') }}" enctype="multipart/form-data" files="true" method="post" autocomplete="off" id="update-version-form">
                @csrf
                <input type="hidden" name="vid">
                <div class="form-group">
                  <label for="version_name">Version Name</label>
                  <input type="text" class="form-control" name="version_name" id="version_name" placeholder="Version Name লিখুন">
                  <span class="text-danger error-text version_name_error"></span>
              </div>
          
              <div class="form-group">
                <label for="status">Status</label>
                <select class="form-control" name="version_status" id="version_status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
                <span class="text-danger error-text version_status_error"></span>
              </div>
            
                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-success">Submit</button>
                </div>
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

<script>
  new DataTable('#data-table');
</script>
    


<script>

$.ajaxSetup({
    headers:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
    }
});


  $(document).ready(function () {
    // Image preview
    $("#upload").change(function () {
        readURL(this);
    });

    
    
      $('#add-version-form').on('submit', function(e){
        e.preventDefault();
        var form = this;
        $.ajax({
            url:$(form).attr('action'),
            method:$(form).attr('method'),
            data:new FormData(form),
            processData:false,
            dataType:'json',
            contentType:false,
            beforeSend:function(){
                  $(form).find('span.error-text').text('');
            },
            success:function(data){
                  if(data.code == 0){
                        $.each(data.error, function(prefix, val){
                            $(form).find('span.'+prefix+'_error').text(val[0]);
                        });
                  }else{
                      var redirectUrl = data.redirect;
                      // $('#brand-table').DataTable().ajax.reload(null, false);
                      $('#addversions').modal('hide');
                      $('#addversions').find('form')[0].reset();
                      toastr.success(data.msg);

                      setTimeout(function() {
                          window.location.href = redirectUrl;
                      }, 1000); // Adjust the delay as needed (in milliseconds)
                  }
            }
        });
    });

    $(document).on('click','#editVersionBtn', function(){
      var version_id = $(this).data('id');
      
      $('.editVersion').find('form')[0].reset();
      $('.editVersion').find('span.error-text').text('');
      $.post("{{ route('admin.getVersionDetails') }}",{version_id:version_id}, function(data){
          //alert(data.details.version_name);
          var linkModal = $('.editVersion');
          $('.editVersion').find('input[name="vid"]').val(data.details.id);
          $('.editVersion').find('input[name="version_name"]').val(data.details.version_name);
          $('.editVersion').find('select[name="version_status"]').val(data.details.version_status);
          $('.editVersion').modal('show');
      },'json');
    });

    $('#update-version-form').on('submit', function(e){
      e.preventDefault();
      var form = this;
      $.ajax({
          url:$(form).attr('action'),
          method:$(form).attr('method'),
          data:new FormData(form),
          processData:false,
          dataType:'json',
          contentType:false,
          beforeSend: function(){
                $(form).find('span.error-text').text('');
          },
          success: function(data){
                if(data.code == 0){
                    $.each(data.error, function(prefix, val){
                        $(form).find('span.'+prefix+'_error').text(val[0]);
                    });
                }else{
                    // $('#category-table').DataTable().ajax.reload(null, false);
                    var redirectUrl = data.redirect;
                    $('.editVersion').modal('hide');
                    $('.editVersion').find('form')[0].reset();
                    toastr.success(data.msg);
                    
                    setTimeout(function() {
                        window.location.href = redirectUrl;
                    }, 1000); // Adjust the delay as needed (in milliseconds)
                    
                }
          }
      });
  });

  //DELETE Version RECORD
  $(document).on('click','#deleteVersionBtn', function(){
      var version_id = $(this).data('id');
      var url = '<?= route("admin.deleteVersion"); ?>';
      swal.fire({
            title:'Are you sure?',
            html:'You want to <b>delete</b> this version name',
            showCancelButton:true,
            showCloseButton:true,
            cancelButtonText:'Cancel',
            confirmButtonText:'Yes, Delete',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            
            allowOutsideClick:false
      }).then(function(result){
            if(result.value){
                $.post(url,{version_id:version_id}, function(data){
                      if(data.code == 1){
                          var redirectUrl = data.redirect;
                          toastr.success(data.msg);
                          setTimeout(function() {
                              window.location.href = redirectUrl;
                          }, 1000); // Adjust the delay as needed (in milliseconds)

                      }else{
                          toastr.error(data.msg);
                      }
                },'json');
            }
      });
  });

});

</script>


    
@endpush

