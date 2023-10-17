@extends('dashboard.admin.layouts.admin-layout')
@section('title', 'Dashboard')

@section('content')
     <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">প্রোফাইল</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">হোম</a></li>
              <li class="breadcrumb-item active">পাসওয়ার্ড পরিবর্তন</li>
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
          <div class="col-lg-6 ">
            <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title">পাসওয়ার্ড পরিবর্তন</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                <form action="{{ route('admin.changepassaction') }}" method="post" autocomplete="off">
                    
                    @if (Session::get('fail'))
                    <div class="alert alert-danger">
                        {{ Session::get('fail') }}
                    </div>
                    @endif

                    @csrf
                  <div class="card-body">
                    <div class="form-group">
                        <label for="exampleInputPassword1">বর্তমান পাসওয়ার্ড</label>
                        <input type="password" class="form-control" id="password" placeholder="বর্তমান পাসওয়ার্ড দিন" name="cur_pass">
                        <span class="text-danger">@error('cur_pass'){{ $message }} @enderror</span>
                      </div>
                    <div class="form-group">
                      <label for="exampleInputPassword1">নতুন পাসওয়ার্ড</label>
                      <input type="password" class="form-control" id="password" placeholder="নতুন পাসওয়ার্ড দিন" name="new_pass">
                      <span class="text-danger">@error('new_pass'){{ $message }} @enderror</span>
                    </div>
                    
                    <div class="form-group">
                        <label for="exampleInputPassword1">নতুন পাসওয়ার্ড নিশ্চিত করুন</label>
                        <input type="password" class="form-control" id="password" placeholder="নতুন পাসওয়ার্ড নিশ্চিত করুন" name="cnew_pass">
                        <span class="text-danger">@error('cnew_pass'){{ $message }} @enderror</span>
                      </div>
                  </div>
                  <!-- /.card-body -->
  
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">প্রদান করুন</button>
                  </div>
                </form>
              </div>
          </div>
          <!-- /.col-md-6 -->
         
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

 
@endsection