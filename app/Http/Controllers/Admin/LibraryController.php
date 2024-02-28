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
            $category->school_id = auth()->user()->school_id;
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
        $send['categories'] = BookCategory::get();
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
            $book->school_id = auth()->user()->school_id;
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



    public function checkStudentBooks(Request $request)
    {
        $studentId = $request->input('std_id');


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

        $student = DB::table('students')->where('std_id', $studentId)->first();

        return response()->json([
            'occupiedBooks' => $issuedBooks,
            'students' => [$student],
        ]);
    }


    public function suggestions(Request $request)
    {
        $query = $request->input('query');

        $books = Book::where('book_title', 'like', "%{$query}%")->limit(10)->get();

        return response()->json($books);
    }

    public function storeBookIssues(Request $request)
    {
        // Step 1: Add a configuration parameter for the maximum number of books
        // $maxBooksAllowed = config('library.max_books_allowed');
        $maxBooksAllowed = 1;
    
        $validator = Validator::make($request->all(), [
            'student.studentId' => 'required',
            'books.*.bookTitle' => 'required',
            'books.*.quantity' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return response()->json(['code' => 0, 'errors' => $validator->errors()->toArray()]);
        }
    
        $studentId = $request->input('student.studentId');
    
        // Step 2: Retrieve the current number of books issued to the student
        $currentBooksIssued = BookIssue::where('student_id', $studentId)->sum('quantity');
    
        // Step 3: Check if the student is allowed to issue more books based on the maximum limit
        if ($currentBooksIssued >= $maxBooksAllowed) {
            return response()->json(['code' => 0, 'error' => 'Student has reached the maximum number of allowed books.']);
        }
    
        $bookEntries = $request->input('books');
        foreach ($bookEntries as $bookEntry) {
            $bookTitle = $bookEntry['bookTitle'];
            $quantity = $bookEntry['quantity'];
    
            $book = Book::where('book_title', $bookTitle)->first();
    
            if ($book) {
                // Step 4: If the student is allowed, proceed with the book issuance
                BookIssue::create([
                    'student_id' => $studentId,
                    'book_id' => $book->id,
                    'quantity' => $quantity,
                    'issue_date' => now(),
                    'due_date' => now()->addDays(14),
                ]);
            } else {
                // Handle the case where the book is not found
                // You can add custom logic or return an error response
            }
        }
    
        return response()->json(['code' => 1, 'msg' => __('language.version_edit_msg'), 'redirect' => 'admin/book-issue']);
    }




    public function getStudentList(Request $request)
    {
        $query = $request->query('query');

        $students = DB::table('academic_students')
            ->join('students', 'academic_students.std_id', '=', 'students.std_id')
            ->where('academic_students.st_aca_status', 1)
            ->where('students.std_id', 'like', '%' . $query . '%')
            ->select('students.std_id', 'students.std_name')
            ->get();

        return response()->json($students);
    }


    public function book_return()
    {
        return view('dashboard.admin.library.book_return');
    }
}
