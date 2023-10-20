@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title',  'মেনু ম্যানেজমেন্ট')
@push('admincss')
<!-- DataTables -->
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@7.28.11/dist/sweetalert2.min.css">
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
            <h1 class="m-0">মেনু ম্যানেজমেন্ট</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">মেনু ম্যানেজমেন্ট</a></li>
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
                          মেনুর তালিকা
                        </h3>
                        <div class="card-tools">
                          <ul class="nav nav-pills ml-auto">
                            <li class="nav-item">
                                
                              <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#addmenus"><i class="fas fa-plus-square mr-1"></i> মেনু যোগ করুন</button>
                            </li>
                          </ul>
                        </div>
                    </div>
                    
                    <div class="card-body p-0">
                        <table class="table table-bordered table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th style="width: 10px">#</th>
                                    <th>Task</th>
                                    <th>Progress</th>
                                    <th style="width: 40px">Label</th>
                                </tr>
                            </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Update software</td>
                                        <td>
                                            <div class="input-group-prepend show">
                                                <i class="fa fa-chevron-circle-down" style="font-size: 18px; margin-right: 10px; color: #1c0258; cursor: pointer;" data-toggle="dropdown" aria-expanded="true" data-bs-toggle="dropdown"></i>
                                                <div class="dropdown-menu" x-placement="top-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(0px, -164px, 0px); border:1px solid #76d6f9
                                                ">
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-edit" style="color: #007bff;"></i> Edit
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-trash" style="color: #dc3545;"></i> Delete
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#">
                                                        <i class="fas fa-print" style="color: #28a745;"></i> Print
                                                    </a>
                                                </div>
                                            </div>
                                            
                                            
                                        </td>
                                        
                                        <td><span class="badge bg-danger">55%</span></td>
                                    </tr>
                                    <tr>
                                        <td>1.</td>
                                        <td>Update software</td>
                                        <td>Update software</td>
                                        
                                        <td><span class="badge bg-danger">55%</span></td>
                                    </tr>
                                    <tr>
                                        <td>1.</td>
                                        <td>Update software</td>
                                        <td>Update software</td>
                                        <td><span class="badge bg-danger">55%</span></td>
                                    </tr>
                                    
                                </tbody>
                        </table>
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




    
@endpush

