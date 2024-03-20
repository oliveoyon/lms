<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <base href="{{ \URL::to('/') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css"
        integrity="sha512-nMNlpuaDPrqlEls3IX/Q56H36qvBASwb3ipuo3MxeWbsQB1881ox0cRv7UPTgBlriqoynt35KjEwgGUeUXIPnw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link href="https://fonts.maateen.me/solaiman-lipi/font.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/css/adminlte.min.css">


    @stack('admincss')
    <link href="{{ asset('dashboard/css/custom.css') }}" rel="stylesheet">

    <!-- Your custom CSS -->


    <style>
        /* Original styling for direct submenus */
        [class*="sidebar-dark-"] .nav-sidebar>.nav-item>.nav-treeview {
            background-color: rgb(0, 0, 0);
        }

        /* Additional styling for nested submenus */
        [class*="sidebar-dark-"] .nav-sidebar>.nav-item>.nav-treeview .nav-treeview {
            background-color: #022142;
            /* Set a different background color for nested submenus */
        }
    </style>
</head>
{{-- <body class="hold-transition sidebar-mini"> --}}
{{-- <body class="sidebar-mini skin-green-light" data-gr-c-s-loaded="true" style="height: auto; min-height: 100%;"> --}}

<body class="sidebar-mini skin-blue-light text-sm" data-gr-c-s-loaded="true" style="height: auto; min-height: 100%;">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i
                            class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="{{ route('admin.home') }}" class="nav-link">{{ __('language.home') }}</a>
                </li>

                <li class="nav-item d-none d-sm-inline-block">
                    <a href="https://iconbangla.net" target="_blank" class="nav-link">{{ __('language.contact') }}</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img height="13px" src="{{ asset('dashboard/img/' . App::getLocale() . '.png') }}"
                            alt="">
                        {{ Config::get('languages')[App::getLocale()] }}
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        @foreach (Config::get('languages') as $lang => $language)
                            @if ($lang != App::getLocale())
                                <a class="dropdown-item" href="{{ route('lang.switch', $lang) }}"><img height="15px"
                                        src="{{ asset('dashboard/img/' . $lang . '.png') }}" alt="">
                                    {{ $language }}</a>
                            @endif
                        @endforeach
                    </div>
                </li>

            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->
                <li class="nav-item">
                    <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                        <i class="fas fa-search"></i>
                    </a>
                    <div class="navbar-search-block">
                        <form class="form-inline">
                            <div class="input-group input-group-sm">
                                <input class="form-control form-control-navbar" type="search" placeholder="Search"
                                    aria-label="Search" id="tags">
                                <div class="input-group-append">
                                    <button class="btn btn-navbar" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </li>


                <!-- Notifications Dropdown Menu -->
                <li class="nav-item dropdown">
                    <a class="nav-link" data-toggle="dropdown" href="#">
                        <i class="far fa-bell"></i>
                        <span class="badge badge-warning navbar-badge">15</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                        <span class="dropdown-header">15 Notifications</span>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item">
                            <i class="fas fa-envelope mr-2"></i> 4 new messages
                            <span class="float-right text-muted text-sm">3 mins</span>
                        </a>
                    </div>
                </li>

            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        {{-- <aside class="main-sidebar sidebar-dark-success elevation-4"> --}}
        <aside class="main-sidebar sidebar-dark-purple elevation-4">
            <!-- Brand Logo -->
            <a href="{{ route('admin.home') }}" class="brand-link">
                <img src="dashboard/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
                    style="opacity: .8">
                <span class="brand-text font-weight-bold">IconBangla</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user panel (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="dashboard/img/avatar.png" class="img-circle elevation-2" alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{ Auth::guard('admin')->user()->name }}</a>
                    </div>
                </div>

                <!-- SidebarSearch Form -->
                {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="অনুসন্ধান করুন" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> --}}

                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar text-sm flex-column" data-widget="treeview" role="menu"
                        data-accordion="false">

                        <!-- Multilevel Menu -->
                        <li class="nav-item has-treeview">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>
                                    {{ __('language.reports') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <!-- Class Reports Heading -->
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-building nav-icon"></i>
                                        <p>
                                            {{ __('language.class_reports') }}
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <!-- Class List Report -->
                                        <li class="nav-item">
                                            <a href="{{ route('admin.class-list-report') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>{{ __('language.class_list_report') }}</p>
                                            </a>
                                        </li>


                                        <!-- Class Enrollment Report -->
                                        <li class="nav-item">
                                            <a href="{{ route('admin.version-wise-enrollment') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>{{ __('language.class_enrollment_report') }}</p>
                                            </a>
                                        </li>
                                        <!-- Version-wise Class Report -->
                                        <li class="nav-item">
                                            <a href="{{ route('admin.version-wise-class-list-report') }}"
                                                class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>{{ __('language.version_wise_class_report') }}</p>
                                            </a>
                                        </li>

                                        <!-- Class Statistics Report -->
                                        <li class="nav-item">
                                            <a href="{{ route('admin.class_statistics') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>{{ __('language.class_statistics_report') }}</p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('admin.class_summery') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>{{ __('language.class_summary_report') }}</p>
                                            </a>
                                        </li>


                                        <li class="nav-item">
                                            <a href="{{ route('admin.class_student_count') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Student Enrollment</p>
                                            </a>
                                        </li>

                                        <li class="nav-item">
                                            <a href="{{ route('admin.subject_list') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Class Wise Subject</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.subject_count') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Class Wise Total Subject</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.section_wise_teacher') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Class Teacher</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.guardian_list') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Guardian Information</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.class_attendance') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Class Attendance Report</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="nav-item has-treeview">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-building nav-icon"></i>
                                        <p>
                                            Fee Reports
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        <!-- Class List Report -->
                                        <li class="nav-item">
                                            <a href="{{ route('admin.FrequencyList') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Fee Frequency List</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.FeeHeadList') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Fee Head List</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.FeeGroupList') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Fee Group List</p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="{{ route('admin.FeeAmountList') }}" class="nav-link">
                                                <i class="far fa-circle nav-icon"></i>
                                                <p>Fee Amount List</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-school"></i>
                                <p>
                                    {{ __('language.class_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.version-list') }}" class="nav-link">
                                        <i class="fas fa-chalkboard nav-icon"></i>
                                        <p>{{ __('language.version') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.class-list') }}" class="nav-link">
                                        <i class="fas fa-chalkboard-teacher nav-icon"></i>
                                        <p>{{ __('language.class') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.section-list') }}" class="nav-link">
                                        <i class="fas fa-stream nav-icon"></i>
                                        <p>{{ __('language.section') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.subject-list') }}" class="nav-link">
                                        <i class="fas fa-book nav-icon"></i>
                                        <p>{{ __('language.subject') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-money-check-alt"></i>
                                <p>
                                    {{ __('language.aca_fee_setup') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.fee-frequency-list') }}" class="nav-link">
                                        <i class="fas fa-money-bill nav-icon"></i>
                                        <p>{{ __('language.fee_frequency') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.academic-fee-head-list') }}" class="nav-link">
                                        <i class="far fa-credit-card nav-icon"></i>
                                        <p>{{ __('language.aca_fee_head') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.academic-fee-group-list') }}" class="nav-link">
                                        <i class="far fa-list-alt nav-icon"></i>
                                        <p>{{ __('language.aca_fee_group') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.academic-fee-amount-list') }}" class="nav-link">
                                        <i class="far fa-money-bill-alt nav-icon"></i>
                                        <p>{{ __('language.fee_amount') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-graduation-cap"></i>
                                <p>
                                    {{ __('language.manage_student') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.admission') }}" class="nav-link">
                                        <i class="fas fa-user-plus nav-icon"></i>
                                        <p>{{ __('language.add_student') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.bulkadmission') }}" class="nav-link">
                                        <i class="fas fa-upload nav-icon"></i>
                                        <p>{{ __('language.add_bulk_student') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.enroll') }}" class="nav-link">
                                        <i class="far fa-check-circle nav-icon"></i>
                                        <p>{{ __('language.enroll_student') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.stdlist') }}" class="nav-link">
                                        <i class="far fa-list-alt nav-icon"></i>
                                        <p>{{ __('language.student_list') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-arrow-alt-circle-up nav-icon"></i>
                                        <p>{{ __('language.promote_student') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                <p>
                                    {{ __('language.fees_collection') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.collectFee') }}" class="nav-link">
                                        <i class="fas fa-hand-holding-usd nav-icon"></i>
                                        <p>{{ __('language.collect_fee') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.customFeeGen') }}" class="nav-link">
                                        <i class="fas fa-file-upload nav-icon"></i>
                                        <p>{{ __('language.custom_fee_generate') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-clipboard"></i>
                                <p>
                                    {{ __('language.exam_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-list-alt nav-icon"></i>
                                        <p>{{ __('language.exam_lists') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-clock nav-icon"></i>
                                        <p>{{ __('language.exam_routine') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-id-card nav-icon"></i>
                                        <p>{{ __('language.admit_card') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-list nav-icon"></i>
                                        <p>{{ __('language.result_grade') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-edit nav-icon"></i>
                                        <p>{{ __('language.result_entry') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>{{ __('language.mark_sheet') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-book"></i>
                                <p>
                                    {{ __('language.library_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.book-category-list') }}" class="nav-link">
                                        <i class="far fa-folder-open nav-icon"></i>
                                        <p>{{ __('language.book_category') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.book-list') }}" class="nav-link">
                                        <i class="fas fa-book-open nav-icon"></i>
                                        <p>{{ __('language.book_lists') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.book_issue') }}" class="nav-link">
                                        <i class="fas fa-file-import nav-icon"></i>
                                        <p>{{ __('language.issue_book') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-file-export nav-icon"></i>
                                        <p>{{ __('language.return_book') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-chart-bar nav-icon"></i>
                                        <p>{{ __('language.library_reports') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>
                                    {{ __('language.attendance_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.attendanceInput') }}" class="nav-link">
                                        <i class="fas fa-pencil-alt nav-icon"></i>
                                        <p>{{ __('language.attendance_input') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.attendanceEdit') }}" class="nav-link">
                                        <i class="fas fa-edit nav-icon"></i>
                                        <p>{{ __('language.attendance_edit') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                                <p>
                                    {{ __('language.teacher_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.teacher-list') }}" class="nav-link">
                                        <i class="far fa-list-alt nav-icon"></i>
                                        <p>{{ __('language.teacher_lists') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.assigned-teacher-list') }}" class="nav-link">
                                        <i class="fas fa-user-plus nav-icon"></i>
                                        <p>{{ __('language.assign_teacher_to_course') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-calendar-alt"></i>
                                <p>
                                    {{ __('language.routine_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.showClassRoutine') }}" class="nav-link">
                                        <i class="far fa-clock nav-icon"></i>
                                        <p>{{ __('language.view_class_period') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.createClassRoutine') }}" class="nav-link">
                                        <i class="far fa-clock nav-icon"></i>
                                        <p>{{ __('language.create_class_period') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.createRoutine') }}" class="nav-link">
                                        <i class="far fa-calendar nav-icon"></i>
                                        <p>{{ __('language.class_routine') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-calendar-check"></i>
                                <p>
                                    {{ __('language.notice_events_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.event-list') }}" class="nav-link">
                                        <i class="fas fa-bullhorn nav-icon"></i>
                                        <p>{{ __('language.notice_events') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-bus"></i>
                                <p>
                                    {{ __('language.transport_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.stopage-list') }}" class="nav-link">
                                        <i class="fas fa-map-pin nav-icon"></i>
                                        <p>{{ __('language.stoppages') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.vehicletype-list') }}" class="nav-link">
                                        <i class="fas fa-car-alt nav-icon"></i>
                                        <p>{{ __('language.vehicle_types') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.vehicle-list') }}" class="nav-link">
                                        <i class="fas fa-bus nav-icon"></i>
                                        <p>{{ __('language.vehicles_lists') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.route-list') }}" class="nav-link">
                                        <i class="fas fa-route nav-icon"></i>
                                        <p>{{ __('language.routes_lists') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('admin.assignStdTrans') }}" class="nav-link">
                                        <i class="fas fa-user-plus nav-icon"></i>
                                        <p>{{ __('language.assign_students') }}</p>
                                    </a>
                                </li>
                                <!-- Add the new items below -->
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-clipboard nav-icon"></i>
                                        <p>{{ __('language.transport_fee_heads') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-money-check-alt nav-icon"></i>
                                        <p>{{ __('language.transport_fee_amounts') }}</p>
                                    </a>
                                </li>

                                <!-- End of new items -->
                            </ul>
                        </li>


                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-warehouse"></i>
                                <p>
                                    {{ __('language.inventory_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-boxes nav-icon"></i>
                                        <p>{{ __('language.product_categories') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-balance-scale nav-icon"></i>
                                        <p>{{ __('language.units') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-store nav-icon"></i>
                                        <p>{{ __('language.stores') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-truck nav-icon"></i>
                                        <p>{{ __('language.vendors') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-box nav-icon"></i>
                                        <p>{{ __('language.products') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-shopping-cart nav-icon"></i>
                                        <p>{{ __('language.product_purchases') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-users nav-icon"></i>
                                        <p>{{ __('language.recipients') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-handshake nav-icon"></i>
                                        <p>{{ __('language.issue_products') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-money-bill-alt"></i>
                                <p>
                                    {{ __('language.expenditure_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-list-ul nav-icon"></i>
                                        <p>{{ __('language.expenditure_types') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-file-invoice-dollar nav-icon"></i>
                                        <p>{{ __('language.expenditure_bills') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-chart-pie nav-icon"></i>
                                        <p>{{ __('language.expenditure_reports') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-certificate"></i>
                                <p>
                                    {{ __('language.certificate_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>{{ __('language.testimonial') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-file-excel nav-icon"></i>
                                        <p>{{ __('language.tabulation_sheet') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>{{ __('language.mark_sheet') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-chart-bar nav-icon"></i>
                                        <p>{{ __('language.grade_report') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-hotel"></i>
                                <p>
                                    {{ __('language.hostel_mgmt') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-list-alt nav-icon"></i>
                                        <p>{{ __('language.hostel_lists') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-building nav-icon"></i>
                                        <p>{{ __('language.room_lists') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="fas fa-user-plus nav-icon"></i>
                                        <p>{{ __('language.assign_students') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-edit"></i>
                                <p>
                                    {{ __('language.assignments_homeworks') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>{{ __('language.assignments_homeworks') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon fas fa-cog"></i>
                                <p>
                                    {{ __('language.settings') }}
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <li class="nav-item">
                                    <a href="{{ route('admin.genSetting') }}" class="nav-link">
                                        <i class="fas fa-sliders-h nav-icon"></i>
                                        <p>{{ __('language.general_settings') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-file-alt nav-icon"></i>
                                        <p>{{ __('language.fine_settings') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-envelope nav-icon"></i>
                                        <p>{{ __('language.email_settings') }}</p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link">
                                        <i class="far fa-comment-dots nav-icon"></i>
                                        <p>{{ __('language.sms_settings') }}</p>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="nav-icon far fa-envelope"></i>
                                <p>
                                    {{ __('language.mailbox') }}
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('admin.logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();"
                                class="nav-link">
                                <i class="nav-icon fas fa-sign-out-alt"></i>
                                <p>{{ __('language.logout') }}</p>
                                <form action="{{ route('admin.logout') }}" id="logout-form" method="post">@csrf
                                </form>
                            </a>
                        </li>

                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        @yield('content')


        <div id="loader-overlay">
            <div id="loader"></div>
        </div>

        <!-- Main Footer -->
        <footer class="main-footer">
            <!-- To the right -->
            <div class="float-right d-none d-sm-inline">
                Developed By: IconBangla
            </div>
            <!-- Default to the left -->
            <strong>Copyright &copy; <?php echo date('Y'); ?> <a href="">EduFlow</a></strong> All Right Reserved
        </footer>
    </div>
    <!-- ./wrapper -->



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.1.0/js/adminlte.min.js"></script>



    @stack('adminjs')

    <script type="text/javascript">
        // AdminLTe 3.0.x
        /** add active class and stay opened when selected */
        var url = window.location;

        // for sidebar menu entirely but not cover treeview
        $('ul.nav-sidebar a').filter(function() {
            return this.href == url;
        }).addClass('active');

        // for treeview
        $('ul.nav-treeview a').filter(function() {
            return this.href == url;
        }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });
    </script>





</body>

</html>
