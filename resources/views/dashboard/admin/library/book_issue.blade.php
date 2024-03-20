@extends('dashboard.admin.layouts.admin-layout-with-cdn')
@section('title', 'Version')
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
                    <h1 class="m-0">{{ __('language.book_category') }}</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('language.home') }}</a></li>
                        <li class="breadcrumb-item active">{{ __('language.book_category') }}</li>
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
                <!-- Left Column: Student and Book Search Form -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Student and Book Search</h3>
                        </div>
                        <div class="card-body">
                            <!-- Student Search Form -->
                            <form id="yourFormId" action="{{ route('admin.storeBookIssues') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="studentId">Student ID</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="studentId" name="student.studentId" placeholder="Enter student ID">
                                    </div>
                                </div>

                                <!-- Display Student Suggestions -->
                                <div id="studentSuggestions" class="dropdown-list"></div>

                                <!-- Display Occupied Books Table -->
                                <div id="occupiedBooksTable">
                                    <!-- The table will be displayed here -->
                                </div>

                                <!-- Book Name Search and Form -->
                                <div class="form-group">
                                    <label for="bookName">Book Name</label>
                                    <input type="text" class="form-control" id="bookName" autocomplete="off">
                                    <div id="bookList" class="dropdown-list"></div>
                                </div>

                                <!-- Book Rows -->
                                <table class="table mt-3">
                                    <thead>
                                        <tr>
                                            <th>Sl</th>
                                            <th>Book Title</th>
                                            <th>Quantity</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bookRowsContainer">
                                        <!-- Book rows will be dynamically added here -->
                                    </tbody>
                                </table>

                                <!-- Submit Button -->
                                <button type="button" class="btn btn-primary" onclick="submitBookIssueForm()">Submit</button>

                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Book Details</h3>
                        </div>
                        <div class="card-body">
                            <!-- Book Details Content -->
                            <div id="studentInfo">
                                <!-- Content will be dynamically updated here -->
                            </div>
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
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>

<script>
    $(document).ready(function() {
        var studentIdInput = $('#studentId');
        var studentSuggestionsDiv = $('#studentSuggestions');
        var studentInfoElement = $('#studentInfo');
        var bookNameInput = $('#bookName');
        var bookListDiv = $('#bookList');

        // Student ID Input Events
        studentIdInput.on('input', function() {
            var enteredText = studentIdInput.val().trim();
            if (enteredText !== '') {
                fetchStudentSuggestions(enteredText);
            } else {
                clearStudentSuggestions();
            }
        });

        // Fetch Student Suggestions
        function fetchStudentSuggestions(enteredText) {
            $.ajax({
                url: '{{ route("admin.getStudentList") }}',
                method: 'GET',
                data: {
                    query: enteredText
                },
                success: function(data) {
                    displayStudentSuggestions(data);
                },
                error: function(error) {
                    console.error('Error fetching student suggestions:', error);
                }
            });
        }

        // Display Student Suggestions
        function displayStudentSuggestions(studentSuggestions) {
            clearStudentSuggestions();
            if (studentSuggestions.length > 0) {
                studentSuggestions.forEach(function(student) {
                    var suggestionItem = $('<div class="dropdown-item">' + student.std_id + ' - ' + student.std_name + '</div>');
                    suggestionItem.on('click', function() {
                        selectStudent(student);
                    });
                    studentSuggestionsDiv.append(suggestionItem);
                });
                studentSuggestionsDiv.show();
            } else {
                studentSuggestionsDiv.hide();
            }
        }

        function selectStudent(student) {
            // Set the selected student ID in the input field
            studentIdInput.val(student.std_id);

            // Clear the student suggestions and hide the dropdown list
            clearStudentSuggestions();

            // Fetch occupied books based on the selected student ID
            fetchOccupiedBooks(student.std_id);
        }



        // Clear Student Suggestions
        function clearStudentSuggestions() {
            studentSuggestionsDiv.empty().hide();
        }

        // Fetch Occupied Books
        function fetchOccupiedBooks(studentId) {
            $.ajax({
                url: '{{ route("admin.checkStudentBooks") }}',
                method: 'POST',
                data: {
                    studentId: studentId
                },
                success: function(data) {
                    displayOccupiedBooks(data);
                },
                error: function(error) {
                    console.error('Error fetching occupied books:', error);
                }
            });
        }

        // Display Occupied Books
        function displayOccupiedBooks(bookData) {
            clearRightColumn();

            if (bookData.students.length > 0) {
                var student = bookData.students[0]; // Assuming there is only one student in the array

                console.log(bookData.occupiedBooks);

                var studentInfoHTML = '<h3>Student Information</h3>';
                studentInfoHTML += '<p><strong>Student ID:</strong> ' + student.std_id + '</p>';
                studentInfoHTML += '<p><strong>Student Name:</strong> ' + student.std_name + '</p>';

                studentInfoHTML += '<h3>Occupied Books</h3>';
                studentInfoHTML += '<table class="table">';
                studentInfoHTML += '<thead><tr><th>Book Title</th><th>Quantity</th><th>Issue Date</th><th>Due Date</th></tr></thead>';
                studentInfoHTML += '<tbody>';

                bookData.occupiedBooks.forEach(function(book) {
                    studentInfoHTML += '<tr>';
                    studentInfoHTML += '<td>' + book.book_title + '</td>';
                    studentInfoHTML += '<td>' + book.quantity + '</td>';
                    studentInfoHTML += '<td>' + book.issue_date + '</td>';
                    studentInfoHTML += '<td>' + book.due_date + '</td>';
                    studentInfoHTML += '</tr>';
                });

                studentInfoHTML += '</tbody></table>';

                studentInfoElement.html(studentInfoHTML);
            } else {
                studentInfoElement.html('<p>No student information available.</p>');
            }
        }


        // Clear Right Column
        function clearRightColumn() {
            studentInfoElement.html('');
        }

        // Book Name Input Events
        bookNameInput.on('input', function() {
            var enteredText = bookNameInput.val().trim();
            if (enteredText !== '') {
                fetchBookSuggestions(enteredText);
            } else {
                clearBookList();
            }
        });

        // Fetch Book Suggestions
        function fetchBookSuggestions(enteredText) {
            var apiUrl = '{{ route("admin.suggestions") }}';
            $.ajax({
                url: apiUrl,
                method: 'GET',
                data: {
                    query: enteredText
                },
                success: function(data) {
                    displayBookSuggestions(data);
                },
                error: function(error) {
                    console.error('Error fetching book suggestions:', error);
                }
            });
        }

        // Display Book Suggestions
        function displayBookSuggestions(bookSuggestions) {
            clearBookList();
            if (bookSuggestions.length > 0) {
                bookSuggestions.forEach(function(book) {
                    var suggestionItem = $('<div class="dropdown-item">' + book.book_title + '</div>');
                    suggestionItem.on('click', function() {
                        selectBook(book);
                    });
                    bookListDiv.append(suggestionItem);
                });
                bookListDiv.show();
            } else {
                bookListDiv.hide();
            }
        }

        // Select Book
        function selectBook(book) {
            var sl = $('#bookRowsContainer tr').length + 1;
            var bookDetailsRow = $(
                '<tr>' +
                '<td>' + sl + '</td>' +
                '<td>' + book.book_title + '</td>' +
                '<td><input type="number" class="form-control" name="quantity[]" placeholder="Quantity"></td>' +
                '<td><button type="button" class="btn btn-danger" onclick="removeBookRow(this)">Remove</button></td>' +
                '</tr>'
            );
            $('#bookRowsContainer').append(bookDetailsRow);
            $('#bookName').val('');
            clearBookList();
        }

        // Remove Book Row
        function removeBookRow(button) {
            $(button).closest('tr').remove();
            updateSlNumbers();
        }

        // Update SL Numbers
        function updateSlNumbers() {
            $('#bookRowsContainer tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }

        // Clear Book List
        function clearBookList() {
            bookListDiv.empty().hide();
        }

        // Submit Book Issue Form
        function submitBookIssueForm() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var studentData = {
                studentId: $('#studentId').val(),
            };
            var bookEntriesData = [];
            $('#bookRowsContainer tr').each(function(index, row) {
                var bookEntry = {
                    bookTitle: $(row).find('td:eq(1)').text(),
                    quantity: $(row).find('input[name="quantity[]"]').val(),
                    remarks: $(row).find('input[name="remarks[]"]').val(),
                };
                bookEntriesData.push(bookEntry);
            });
            var formData = {
                student: studentData,
                books: bookEntriesData,
            };
            $.ajax({
                url: '{{ route("admin.storeBookIssues") }}',
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                },
                data: formData,
                success: function(response) {
                    console.log('Form submitted successfully:', response);
                },
                error: function(error) {
                    console.error('Error submitting form:', error);
                }
            });
        }
    });
</script>



<script>
    function submitBookIssueForm() {
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        // Show the loader overlay
        $('#loader-overlay').show();

        var studentData = {
            studentId: $('#studentId').val(),
        };
        var bookEntriesData = [];
        $('#bookRowsContainer tr').each(function(index, row) {
            var bookEntry = {
                bookTitle: $(row).find('td:eq(1)').text(),
                quantity: $(row).find('input[name="quantity[]"]').val(),
            };
            bookEntriesData.push(bookEntry);
        });
        var formData = {
            student: studentData,
            books: bookEntriesData,
        };

        $.ajax({
            url: '{{ route("admin.storeBookIssues") }}', // Replace with your actual route
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: formData,
            success: function(data) {
                if (data.code === 0 && data.errors) {
                    $.each(data.errors, function(field, messages) {
                        console.error(field, messages);
                        toastr.error(messages[0]); // Display the first error message for each field
                    });
                } else if (data.code === 0 && data.error) {
                    console.error('Error:', data.error);
                    toastr.error(data.error); // Display a general error message
                } else {
                    // Handle success
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }

                    toastr.success(data.msg);
                }

                // Hide the loader overlay
                $('#loader-overlay').hide();
            },
            error: function(error) {
                console.error('Error submitting form:', error);

                // Hide the loader overlay on error
                $('#loader-overlay').hide();
            }
        });

    }

    $(document).ready(function() {
        // ... (previous code)


        // Input field for student ID
        var studentIdInput = $('#studentId');

        // Right column for occupied books information
        var studentInfoElement = $('#studentInfo');



        function fetchOccupiedBooks(studentId) {
            // Make an AJAX request to fetch occupied books
            $.ajax({
                url: '{{ route("admin.checkStudentBooks") }}',
                method: 'POST',
                data: {
                    studentId: studentId
                },
                success: function(data) {
                    // Display the fetched occupied books
                    displayOccupiedBooks(data);
                },
                error: function(error) {
                    console.error('Error fetching occupied books:', error);
                }
            });
        }

        function displayOccupiedBooks(occupiedBooks) {
            // Clear the previous occupied books
            clearRightColumn();

            // Display the new occupied books
            if (occupiedBooks.length > 0) {
                var firstBook = occupiedBooks[0];

                // Append the content to the right-side column
                studentInfoElement.html(
                    '<h3>Book Details</h3>' +
                    '<p><strong>Book Title:</strong> ' + firstBook.book.title + '</p>' +
                    '<p><strong>Quantity:</strong> ' + firstBook.quantity + '</p>' +
                );
            } else {
                // If there are no occupied books, display a message
                studentInfoElement.html('<p>No books currently occupied by this student.</p>');
            }
        }


        function clearRightColumn() {
            // Clear the content of the right-side column
            studentInfoElement.html('');
        }

        // Input field for book name
        var bookNameInput = $('#bookName');

        // Dropdown list for book suggestions
        var bookListDiv = $('#bookList');

        // Attach an event listener to the book name input
        bookNameInput.on('input', function() {
            // Fetch book suggestions based on the entered text
            var enteredText = bookNameInput.val().trim();
            if (enteredText !== '') {
                fetchBookSuggestions(enteredText);
            } else {
                // If the input is empty, clear the dropdown list
                clearBookList();
            }
        });

        $('#bookRowsContainer').on('click', '.btn-danger', function() {
            removeBookRow(this);
        });

        function fetchBookSuggestions(enteredText) {
            // TODO: Implement a function to fetch book suggestions from the server
            // For example, you can use AJAX to call a route that returns book suggestions
            // Update the URL below with the appropriate route
            var apiUrl = '{{ route("admin.suggestions") }}';

            // Make an AJAX request to fetch book suggestions
            $.ajax({
                url: apiUrl,
                method: 'GET',
                data: {
                    query: enteredText
                },
                success: function(data) {
                    // Display the fetched book suggestions
                    displayBookSuggestions(data);
                },
                error: function(error) {
                    console.error('Error fetching book suggestions:', error);
                }
            });
        }

        function displayBookSuggestions(bookSuggestions) {
            // Clear the previous suggestions
            clearBookList();

            // Display the new suggestions
            if (bookSuggestions.length > 0) {
                bookSuggestions.forEach(function(book) {
                    var suggestionItem = $('<div class="dropdown-item">' + book.book_title + '</div>');

                    // Handle book selection
                    suggestionItem.on('click', function() {
                        selectBook(book);
                    });

                    bookListDiv.append(suggestionItem);
                });

                // Show the dropdown list
                bookListDiv.show();
            } else {
                // If there are no suggestions, hide the dropdown list
                bookListDiv.hide();
            }
        }

        function selectBook(book) {
            // Create a new row with book details
            var sl = $('#bookRowsContainer tr').length + 1; // Increment for each new row
            var bookDetailsRow = $(
                '<tr>' +
                '<td>' + sl + '</td>' +
                '<td>' + book.book_title + '</td>' +
                '<td><input type="number" class="form-control" name="quantity[]" placeholder="Quantity"></td>' +
                '<td><button type="button" class="btn btn-danger" onclick="removeBookRow(this)">Remove</button></td>' +
                '</tr>'
            );

            // Append the new row to the container
            $('#bookRowsContainer').append(bookDetailsRow);

            // Clear the book name field and hide the book list
            $('#bookName').val('');
            clearBookList();
        }

        function removeBookRow(button) {
            $(button).closest('tr').remove();
            // Update the SL numbers after removal
            updateSlNumbers();
        }


        function updateSlNumbers() {
            // Update the SL numbers in the first column of the table
            $('#bookRowsContainer tr').each(function(index) {
                $(this).find('td:first').text(index + 1);
            });
        }


        function clearBookList() {
            // Clear the dropdown list and hide it
            bookListDiv.empty().hide();
        }






        // ... (remaining code)
    });
</script>





@endpush
