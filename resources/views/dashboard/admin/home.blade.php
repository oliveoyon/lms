@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Dashboard')
@push('admincss')
<link rel="stylesheet" href="dist/css/custom.css">
@endpush

@section('content')
     <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
            <!-- Small boxes (Stat box) -->
            <div class="row">
              <div class="col-12 col-sm-6 col-md-3" id="totalsale">
                <div class="info-box">
                  <span class="info-box-icon bg-success elevation-1"><i class="fas fa-shopping-cart"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Today's Sale</span>
                    <span class="info-box-number">
                    
                    </span>
                  </div>
                </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-3" id="totalpurchase">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-warning elevation-1"><i class="fas fa-shopping-basket"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Today's Purchase</span>
                    <span class="info-box-number"></span>
                  </div>
                </div>
              </div>
              
              
              <div class="clearfix hidden-md-up"></div>
                <div class="col-12 col-sm-6 col-md-3" id="receivale">
                  <div class="info-box mb-3">
                    <span class="info-box-icon bg-success elevation-1"><i class="fas fa-user-friends"></i></span>
                    <div class="info-box-content">
                      <span class="info-box-text">Total Receivable</span>
                      <span class="info-box-number"></span>
                    </div>
                  </div>
              </div>
              
              <div class="col-12 col-sm-6 col-md-3" id="payable">
                <div class="info-box mb-3">
                  <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-luggage-cart"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text">Total Payable</span>
                    <span class="info-box-number"></span> 
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="card card-outline card-success">
                  <div class="card-header">
                      <h3 class="card-title" style="color:black; font-weight:bold">Quick Actions</h3> 
                  </div>
                  <div class="card-body">
                      <a class="btn btn-app bg-secondary" href="" style="color:black"><i class="fas fa-shopping-cart"></i> POS</a>
                      
                     
                  </div>
                </div>
              </div>
            </div>

      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->


  <div class="modal fade modal-fullscreen" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-info">
          <h5 class="modal-title" id="modalTitle"></h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
            <div id="printThis" class="printme">
            <div id="abc"></div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <span class="hello"><input type="button" class="btn btn-success" value="Print" onclick="printDiv()"></span>
          
          <span class="addprint"></span>
        </div>
      </div>
    </div>
  </div>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
    <div class="p-3">
      <h5>Title</h5>
      <p>Sidebar content</p>
    </div>
  </aside>
  <!-- /.control-sidebar -->

  
  
@endsection

@push('adminjs')
<!-- Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
    toastr.options.preventDuplicates = true;
      $.ajaxSetup({
          headers:{
              'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
          }
      });
    
      
    </script>  
  


@endpush