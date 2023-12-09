<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Book;
use App\Models\Admin\BookCategory;
use Illuminate\Http\Request;
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

}
