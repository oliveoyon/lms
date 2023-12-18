@extends('dashboard.user.layouts.user-layout')
@section('title', 'Dashboard')

@section('content')
     <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">ড্যাশবোর্ড</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('user.home') }}">হোম</a></li>
              <li class="breadcrumb-item active">ড্যাশবোর্ড</li>
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
          <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Bordered Table</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th style="width: 40px">Phone</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>1.</td>
                        <td scope="row">{{ Auth::guard('web')->user()->name }}</td>
                        <td>{{ Auth::guard('web')->user()->email }}</td>
                        <td></td>
                        <td>
                            <a href="{{ route('user.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">Logout</a>
                            <form action="{{ route('user.logout') }}" id="logout-form" method="post">@csrf</form>
                        </td>
                      </tr>
                      
                    </tbody>
                  </table>

                  
                  
                  

                  
                </div>
                
              </div>
              <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
         
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

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