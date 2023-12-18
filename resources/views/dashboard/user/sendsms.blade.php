@extends('dashboard.user.layouts.user-layout')
@section('title', 'Send SMS')

@section('content')

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">এসএমএস</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('user.home') }}">হোম</a></li>
              <li class="breadcrumb-item active">এসএমএস পাঠান</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
<!-- Horizontal Form -->
<div class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-6">    
                <div class="card card-success card-outline">
                  
                    <!-- /.card-header -->
                    <!-- form start -->
                    @if (Session::get('error'))
                         <div class="alert alert-danger" id="error-alert">
                             {{ Session::get('error') }}
                         </div>
                    @endif
                    <form class="form-horizontal" method="POST" action="{{ route('user.sendsmsaction') }}" autocomplete="off">
                        @csrf
                    <div class="card-body">
                        <div class="form-group row">
                            <label for="Court Name" class="col-sm-4 control-label">কোর্টের নাম <span style="color:red">*</label>
                            <div class="col-sm-8">
                              <input class="form-control" disabled type="text" value="{{ $crtname->court_name_en }}">
                              <input type="hidden" name="court_id" value="322">
                            </div>
                          </div>
                          <div class="form-group row">
                            <label for="Witness Name" class="col-sm-4 control-label">সাক্ষীর নাম <span style="color:red">*</span></label>
                            <div class="col-sm-8">
                              <input class="form-control witness_name" id="witness_name" name="witness_name[]" required placeholder="Name of Witness" type="text" value="">
                            <span id="wordCount">0</span> /25 Characters
                            </div>
            
                          </div>
                          
            
                          <div class="form-group row">
                            <label for="Phone No" class="col-sm-4 control-label">ফোন নাম্বার <span style="color:red">*</span></label>
                            <div class="col-sm-8">
                            
                            <div class="input-group">
                                <div class="input-group-prepend">
                                  <span class="input-group-text">+88</span>
                                </div>
                                <input type="text" class="form-control"  name="phone_no[]" required placeholder="ফোন নাম্বার লিখুন" type="text" value="" maxlength='11' minlength='11' onkeypress='validate(event)'>
                            </div>
                            </div>
                          </div>
            
                          <div class="user-details">
                            
            
                          </div>
                          
            
                          <div class="form-group row">
                            <label for="Case Number" class="col-sm-4 control-label"></label>
                            <div class="col-sm-8">
                            <input value="আরও সাক্ষী যোগ করুন" class="add_details pull-right bg-success" autocomplete="false" type="button">
                            </div>
                          </div>
            
            
                          <div class="form-group row">
                            <label for="Case Number" class="col-sm-4 control-label">মামলা নং <span style="color:red">*</span></label>
                            <div class="col-sm-8">
                            <input class="form-control case_no" id="case_no" name="case_no" required placeholder="মামলা নং লিখুন" type="text" value="">
                            <span class="text-danger">@error('case_no'){{ $message }}@enderror</span>
                          </div>
                          </div>
            
                          <div class="form-group row">
                            <label for="Hearing Date" class="col-sm-4 control-label">শুনানির তারিখ <span style="color:red">*</span></label>
                            <div class="col-sm-8">
                           
                            <div class="input-group">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                              <input type="text" class="form-control pull-right datepicker start_date" name="start_date" id="start_date" required>
                            </div>
                            </div>
                          </div>
            
                          <div class="form-group row">
                            <label for="Hearing time" class="col-sm-4 control-label">শুনানির সময় <span style="color:red">*</span></label>
                            <div class="col-sm-8">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                              <input type="text" name="time" required id="times" class="form-control timepicker times" value="">
                            </div>
                            </div>
                          </div>
            
                          <div class="form-group row">
                            <label for="Sent time" class="col-sm-4 control-label">এসএমএস সিডিউল টাইম <span style="color:red">*</span></label>
                            <div class="col-sm-8">
                            <div class="input-group">
                              <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                              <select name="schedule_time" id="" class="form-control">
                                <option value="0">এখনি পাঠান</option>
                                <option value="3">শুনানির ৩ দিন আগে</option>
                                <option value="10">শুনানির ১০ দিন আগে</option>
                              </select>
                            </div>
                            </div>
                          </div>
            
                          {{-- auto generated message will be --}}
            
                          
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-success float-right">এসএমএস পাঠান</button>
                    </div>
                    <!-- /.card-footer -->
                    </form>
                  </div>
        </div>
        <div class="col-lg-6">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><strong>স্বয়ংক্রিয় ভাবে তৈরি এসএমএসের নমুনা</strong></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">    
              <div class="form-group" id="AddPassport" >
                <div class="col-sm-8">
                  <p><span class='test'></span>, {{ $crtname->court_name_bn }} আদালতে <span class='test3bn'></span> নং মামলায় আগামী <span class='test1bn'></span> তারিখ <span class='test2bn'></span> টায় আপনার সাক্ষ্যর দিন ধার্য্য রইল।</p>
                </div>
              </div>
            </div>
          </div> 
          
          <button type="button" class="btn btn-info" data-toggle="modal" data-target="#modal-xl">সর্বশেষ পাঠানো এসএমএস</button>

          <div class="modal fade" id="modal-xl">
            <div class="modal-dialog modal-xl">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title">সর্বশেষ পাঠানো এসএমএস</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  <table class="table table-bordered">
                    <thead>
                      <tr>
                        <th style="width: 10px">#</th>
                        <th>ফোন নাম্বার</th>
                        <th>এসএমএস</th>
                        <th>পাঠানোর সময়</th>
                        <th>স্ট্যাটাস</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($sentbyme as $s)
                        <tr>
                          <td>{{ $loop->iteration }}</td>
                          <td>{{ $s->phone_no }}</td>
                          <td>{{ $s->sms }}</td>
                          <td>{{ $s->created_at }}</td>
                          <td>{{ $s->status }}</td>
                        </tr>
                      @endforeach
                      
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer justify-content-between">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
              <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
          </div>
          <!-- /.modal -->
          
          
        </div>


      </div>
    </div>
</div>
</div>

  <!-- /.card -->

 


    
  @endsection