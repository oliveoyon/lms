@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Dashboard')
@push('admincss')
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

        <section class="content">
            <div class="container-fluid">
                <h2 class="text-center">Return Book</h2>
                <form action="{{ route('admin.checkStudentBooks') }}" method="post" id="bill-search">
                    @csrf
                    <div class="row">
                        <div class="col-md-10 offset-md-1">
                            
                            <div class="form-group">
                                <div class="input-group input-group-lg">
                                    <input type="search" name="std_id" class="form-control" placeholder="Student ID" value="">
                                    <div class="input-group-append">
                                        <button type="submit" class="btn btn-lg btn-default">
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="row mt-3">
                    <div class="col-md-10 offset-md-1">
                        <div class="list-group">
                            <div id="generateBill"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- /.content-wrapper -->



@endsection

@push('adminjs')
    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"
        integrity="sha512-2ImtlRlf2VVmiGZsjm9bEyhjGW4dU7B6TNwh/hx/iSByxNENtj3WVE6o/9Lj4TJeVXPi4bnOIMXFIJJAeufa0A=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        toastr.options.preventDuplicates = true;
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

<script>
    $(document).ready(function() {
        $('.select2').select2({
            minimumResultsForSearch: Infinity
        });

        $('#bill-search').on('submit', function(e) {
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
                    // Clear any previous error messages
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    // Clear existing content in the #generateBill container
                    $('#generateBill').html('');

                    // Check if the JSON response contains HTML
                    if (data.students.length > 0) {
                        // Display student information
                        displayStudentInfo(data.students[0]);

                        // Display occupied books list
                        displayOccupiedBooks(data.occupiedBooks);
                    } else {
                        // Handle the case when no student data is received
                        console.error('No student data received in the response.');
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    // Handle AJAX error
                    console.error('Error:', errorThrown);
                },
                complete: function() {
                    // Enable the submit button and hide the loader overlay
                    $(form).find(':submit').prop('disabled', false);
                    $('#loader-overlay').hide();
                }
            });
        });

        var generateBillContainer = $('#generateBill');

    function displayStudentInfo(student) {
        var studentCard = $('<div class="card card-primary card-outline">');
        var cardBody = $('<div class="card-body">');
        var html = '<h5 class="card-title">' + student.std_name + '</h5>';
        html += '<p class="card-text">Email: ' + student.std_email + '</p>';
        html += '<p class="card-text">Phone: ' + student.std_phone + '</p>';
        // Add more details as needed...

        cardBody.html(html);
        studentCard.append(cardBody);
        generateBillContainer.append(studentCard);
    }

    function displayOccupiedBooks(occupiedBooks) {
        var booksCard = $('<div class="card card-primary card-outline">');
        var cardBody = $('<div class="card-body">');
        var html = '<h5 class="card-title">Occupied Books</h5>';
        html += '<table class="table table-bordered table-hover">';
        html += '<thead><tr><th>Book Title</th><th>Issue Date</th><th>Return Date</th><th>Fine</th><th>Return</th></tr></thead>';
        html += '<tbody>';

        for (var i = 0; i < occupiedBooks.length; i++) {
            var book = occupiedBooks[i];
            html += '<tr>';
            html += '<td>' + book.book_title + '</td>';
            html += '<td>' + book.issue_date + '</td>';
            html += '<td>' + book.due_date + '</td>';
            html += '<td>' + book.fine_amount + '</td>';
            html += '<td><button class="btn btn-danger" onclick="returnBook(' + book.id + ')">Return</button></td>';
            html += '</tr>';
        }

        html += '</tbody></table>';
        cardBody.html(html);
        booksCard.append(cardBody);
        generateBillContainer.append(booksCard);
    }

    function returnBook(bookId) {
        // Implement the logic for returning a book using AJAX or other means
        console.log('Returning book with ID: ' + bookId);
        // You might want to update the UI or perform additional actions here
    }
    });
</script>



@endpush
