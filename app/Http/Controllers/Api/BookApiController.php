<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Book;
use Illuminate\Support\Facades\Validator;

class BookApiController extends Controller
{
    public function list() {
    	$get = DB::table('book_details')->orderBy('id','desc')->get();
    	return response()->json([
    		'status' => true,
    		'data'   => $get
    	]);
    }

    public function create(Request $request) {
    	$data = $request->only('book','author');
        $validator = Validator::make($data, [
            'book' => 'required|string',
            'author' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $insert = array();
        $insert['book'] = $request->post('book');
        $insert['author'] = $request->post('author');
        DB::table('book_details')->insert($insert);

        return response()->json([
        	'status' => true,
        	'message' => 'Book created successfully',
        	'book-data' => array('book'=>$request->post('book'),'author'=>$request->post('author'))
        ]);
    }

    public function view($id) {
    	$data = DB::table('book_details')->where('id',$id)->first();
    	if (!empty($data)) {
    		return response()->json([
    			'status' => true,
    			'books_details' => $data
    		]);
    	} else {
    		return response()->json([
    			'status' => false,
    			'message' => 'No books found.'
    		]);
    	}
    }

    public function update(Request $request,$id) {
    	$data = DB::table('book_details')->where('id',$id)->first();
    	if (!empty($data)) {
    		$update = array();
    		$update['book'] = $request->post('book');
    		$update['author'] = $request->post('author');
    		DB::table('book_details')->where('id',$id)->update($update);
    		$updatedData = DB::table('book_details')->where('id',$id)->first();
    		return response()->json([
    			'status' => true,
    			'message' => 'Details updated successfully',
    			'updated-data' => $updatedData
    		]);
    	} else {
    		return response()->json([
    			'status' => false,
    			'message' => 'No record found.'
    		]);
    	}
    }

    public function delete($id) {
    	$data = DB::table('book_details')->where('id',$id)->first();
    	if (!empty($data)) {
    		DB::table('book_details')->where('id',$id)->delete();
    		return response()->json([
    			'status' => true,
    			'message' => 'Book deleted successfully'
    		]);
    	} else {
    		return response()->json([
    			'status' => false,
    			'message' => 'No record found.'
    		]);
    	}
    }
}
