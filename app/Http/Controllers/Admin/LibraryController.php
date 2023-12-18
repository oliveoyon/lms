<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\AcademicStudent;
use App\Models\Admin\Book;
use App\Models\Admin\BookCategory;
use App\Models\Admin\BookIssue;
use App\Models\Admin\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LibraryController extends Controller
{
    public function bookCategoryList()
    {
        $send['categories'] = BookCategory::get();
        return view('dashboard.admin.library.book_category', $send);
    }

    public function addBookCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255',
            'category_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $category = new BookCategory();
            $category->book_cat_hash_id = md5(uniqid(rand(), true));
            $category->book_category_name = $request->input('category_name');
            $category->book_cat_status = $request->input('category_status');
            $category->school_id = auth()->user()->school_id; // Assuming you have a school_id in your authentication setup
            $query = $category->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => __('language.something_went_wrong')]);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.category_add_msg'), 'redirect' => 'admin/book-category-list']);
            }
        }
    }

    public function getBookCategoryDetails(Request $request)
    {
        $category_id = $request->category_id;
        $categoryDetails = BookCategory::find($category_id);
        
        // Assuming you have language keys for 'Category Details' and 'Something went wrong'
        return response()->json(['details' => $categoryDetails]);
    }

    public function updateBookCategoryDetails(Request $request)
    {
        $category_id = $request->category_id;
        $category = BookCategory::find($category_id);

        $validator = Validator::make($request->all(), [
            'category_name' => 'required|string|max:255|unique:book_categories,book_category_name,' . $category_id,
            'category_status' => 'required',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $category->book_category_name = $request->input('category_name');
            $category->book_cat_status = $request->input('category_status');
            $query = $category->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => __('language.something_went_wrong')]);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.category_update_msg'), 'redirect' => 'admin/book-category-list']);
            }
        }
    }

    public function deleteBookCategory(Request $request)
    {
        $category_id = $request->category_id;
        $query = BookCategory::find($category_id);
        $query = $query->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.category_delete_msg'), 'redirect' => 'admin/book-category-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => __('language.something_went_wrong')]);
        }
    }

    public function bookList()
    {
        $send['books'] = Book::get();
        $send['categories'] = BookCategory::get(); // Assuming you have a Book model for books
        return view('dashboard.admin.library.book_list', $send);
    }

    public function addBook(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'book_cat_id' => 'required|exists:book_categories,id',
            'book_title' => 'required|string|max:200',
            'author' => 'required|string|max:500',
            'isbn' => 'required|string|max:100',
            'edition' => 'required|string|max:50',
            'publisher' => 'required|string|max:50',
            'shelf' => 'required|string|max:50',
            'position' => 'required|string|max:50',
            'book_purchase_date' => 'required|date',
            'cost' => 'required|numeric',
            'no_of_copy' => 'required|integer',
            'availability' => 'required|string|max:10',
            'language' => 'required|string|max:15',
            'book_status' => 'required|integer',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $book = new Book($request->all());
            $book->book_hash_id = md5(uniqid(rand(), true));
            $book->school_id = auth()->user()->school_id; // Assuming you have a school_id in your authentication setup
            $query = $book->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => __('language.something_went_wrong')]);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.book_add_msg'), 'redirect' => 'admin/book-list']);
            }
        }
    }

    public function getBookDetails(Request $request)
    {
        $book_id = $request->book_id;
        $bookDetails = Book::find($book_id);
        
        // Assuming you have language keys for 'Book Details' and 'Something went wrong'
        return response()->json(['details' => $bookDetails]);
    }

    public function updateBookDetails(Request $request)
    {
        $book_id = $request->book_id;
        $book = Book::find($book_id);

        $validator = Validator::make($request->all(), [
            'book_cat_id' => 'required|exists:book_categories,id',
            'book_title' => 'required|string|max:200',
            'author' => 'required|string|max:500',
            'isbn' => 'required|string|max:100',
            'edition' => 'required|string|max:50',
            'publisher' => 'required|string|max:50',
            'shelf' => 'required|string|max:50',
            'position' => 'required|string|max:50',
            'book_purchase_date' => 'required|date',
            'cost' => 'required|numeric',
            'no_of_copy' => 'required|integer',
            'availability' => 'required|string|max:10',
            'language' => 'required|string|max:15',
            'book_status' => 'required|integer',
        ]);

        if (!$validator->passes()) {
            return response()->json(['code' => 0, 'error' => $validator->errors()->toArray()]);
        } else {
            $book->fill($request->all());
            $query = $book->save();

            if (!$query) {
                return response()->json(['code' => 0, 'msg' => __('language.something_went_wrong')]);
            } else {
                return response()->json(['code' => 1, 'msg' => __('language.book_update_msg'), 'redirect' => 'admin/book-list']);
            }
        }
    }

    public function deleteBook(Request $request)
    {
        $book_id = $request->book_id;
        $query = Book::find($book_id);
        $query = $query->delete();

        if ($query) {
            return response()->json(['code' => 1, 'msg' => __('language.book_delete_msg'), 'redirect' => 'admin/book-list']);
        } else {
            return response()->json(['code' => 0, 'msg' => __('language.something_went_wrong')]);
        }
    }

    public function book_issue()
    {
        return view('dashboard.admin.library.book_issue');
    }

    public function checkBookDetails(Request $request)
    {
        // Validate the request
        $request->validate([
            'book_id' => 'required|string', // Add more validation rules as needed
        ]);

        // Perform logic to retrieve book details from your database based on $request->input('book_id')
        // Replace this with your actual logic to fetch book details

        // Example response (replace this with the actual book details from your database)
        $bookDetails = [
            'title' => 'Sample Book Title',
            'author' => 'Sample Author',
            // Add more details as needed
        ];

        return response()->json($bookDetails);
    }

    public function checkStudentBooks(Request $request)
    {
        $studentId = $request->input('studentId');

        // Join the book_issues table with the books table based on book_id
        $issuedBooks = DB::table('book_issues')
            ->join('books', 'book_issues.book_id', '=', 'books.id')
            ->where('book_issues.student_id', $studentId)
            ->where('book_issues.issue_status', 'issued')
            ->select('book_issues.*', 'books.book_title')
            ->get();

        foreach ($issuedBooks as $book) {
            $book->issue_date = Carbon::parse($book->issue_date)->format('j F, Y');
            $book->due_date = Carbon::parse($book->due_date)->format('j F, Y');
        }


        // Retrieve the student information
        $student = DB::table('students')->where('std_id', $studentId)->first();

        return response()->json([
            'occupiedBooks' => $issuedBooks,
            'students' => [$student], // Return student information as an array
        ]);
    }

    


    public function suggestions(Request $request)
    {
        $query = $request->input('query');

        // Perform a simple query to find books that match the entered text
        $books = Book::where('book_title', 'like', "%{$query}%")->limit(10)->get();

        // Return the book suggestions as JSON
        return response()->json($books);
    }

    public function storeBookIssues(Request $request)
{
    // Validate the incoming request data
    $request->validate([
        'student.studentId' => 'required', // Adjust the validation rules based on your requirements
        'books.*.bookTitle' => 'required',
        'books.*.quantity' => 'required|numeric',
    ]);

    // Retrieve and store the student data
    $studentId = $request->input('student.studentId');
    // Additional student data can be retrieved and stored here

    // Retrieve and store the book entries
    $bookEntries = $request->input('books');
    foreach ($bookEntries as $bookEntry) {
        $bookTitle = $bookEntry['bookTitle'];
        $quantity = $bookEntry['quantity'];

        // Assuming you have a model or method to retrieve the book_id based on the title
        $bookId = Book::where('book_title', $bookTitle)->value('id');

        // Ensure the $bookId is not null before creating the BookIssue record
        if ($bookId !== null) {
            // Create a new BookIssue instance with the necessary fields
            BookIssue::create([
                'student_id' => $studentId,
                'book_id' => $bookId,
                'quantity' => $quantity,
                'issue_date' => Carbon::now(), // Uncomment if you want to set the current date as the issue date
                'due_date' => Carbon::now()->addDays(14), // Uncomment if you want to set the due date to 14 days from the issue date
            ]);

            // You may want to perform additional logic or error handling here
        } else {
            // Handle the case where the book with the provided title was not found
            // You may want to log an error, return a response, etc.
        }
    }

    // Return a response as needed
    return response()->json(['message' => 'Form submitted successfully']);
}


    public function getStudentList(Request $request)
    {
        $query = $request->query('query');

        // Fetch student list based on the entered student ID
        $students = DB::table('academic_students')
            ->join('students', 'academic_students.std_id', '=', 'students.std_id')
            ->where('academic_students.st_aca_status', 1)
            ->where('students.std_id', 'like', '%' . $query . '%')
            ->select('students.std_id', 'students.std_name')
            ->get();

        return response()->json($students);
    }

}
