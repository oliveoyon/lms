@extends('dashboard.user.layouts.user-layout')
@section('title', 'Send SMS')

@section('content')
     <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">রিপোর্ট</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('user.home') }}">হোম</a></li>
              <li class="breadcrumb-item active">এসএমএস রিপোর্ট</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    


    <div class="content">
      <div class="container-fluid">

        @if ((Session::get('error')))
            @php
                $class = '';
            @endphp
        @else
            @php
                $class = 'collapsed-card';
             @endphp
        @endif
        <div class="card card-success <?=$class;?> " id="cards">
          <div class="card-header" data-card-widget="collapse">
            <h3 class="card-title">এসএমএস রিপোর্ট</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-plus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="{{ route('user.smsRptChk') }}" method="post" id="add-user-form" autocomplete="off">
              @csrf
            <div class="row">
              
                <div class="col-md-4">
                    <div class="form-group">
                        <label>তারিখ হতে</label>
                        <div class="input-group date">
                            <input class="form-control abc" data-provide="datepicker" name="start_date" value="{{ old('start_date') }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <span class="text-danger">@error('start_date'){{ $message }}@enderror</span>
                    </div>
                </div>
              
                <div class="col-md-4">
                    <div class="form-group">
                        <label>তারিখ পর্যন্ত</label>
                        <div class="input-group date">
                            <input class="form-control" data-provide="datepicker"  name="end_date" value="{{ old('end_date') }}">
                            <div class="input-group-append">
                                <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                            </div>
                        </div>
                        <span class="text-danger">@error('end_date'){{ $message }}@enderror</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>এসএমএস টাইপ</label>
                        <div class="input-group ">
                            <select name="options" class="form-control">
                                <option value="">সর্ব প্রকার</option>
                                <option value="">পাঠানো এসএমএস</option>
                                <option value="">সিডিউল এসএমএস</option>
                            </select>
                        </div>
                    </div>
                    <span class="text-danger">@error('options'){{ $message }}@enderror</span>
                </div>
            </div>
            <!-- /.row -->
            
            <!-- /.row -->
            <div class="row">
              <div class="col-md-3">
                  <div class="form-group">
                    <label for=""></label>
                    <button type="submit" class="btn btn-block btn-info">সাবমিট</button>
                  </div>
                
              </div>
            </div>
            </form>

          </div>
          <!-- /.card-body -->
          
        </div>
        <!-- /.card -->

        <div class="row">
            <div class="col-md-12">

                  <div class="card card-success card-outline">
                      <div class="card-header">ইউজারের তালিকা</div>
                      <div class="card-body table-responsive">
                          <table class="table table-hover table-condensed" id="user-table">
                              <thead>
                                  <th>#</th>
                                  <th>ইউজারের নাম</th>
                                  <th>ইমেইল</th>
                                  <th>জেলার নাম</th>
                                  <th>কোর্টের নাম</th>
                                  {{-- <th>Initial Password</th> --}}
                                  <th>স্ট্যাটাস</th>
                                  <th>একশন <button class="btn btn-sm btn-danger d-none" id="deleteAllBtn">Delete All</button></th>
                              </thead>
                              <tbody></tbody>
                          </table>
                      </div>
                  </div>
            </div>
            
        </div>

        
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

@endsection