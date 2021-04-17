<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class BooksController extends Controller
{
    public function getBooks() {
    	$bookDetails = DB::table('book_details')->orderBy('id','desc')->get()->toArray();
    	return view('booksAuthor',compact('bookDetails'));
    }

    public function addBook(Request $request) {
    	$insert = array();
    	$insert['book'] = $request->post('book');
    	$insert['author'] = $request->post('author');
    	$id = DB::table('book_details')->insertGetId($insert);
        $data = DB::table('book_details')->where('id',$id)->first();
        $response = array("status"=>"success","id"=>$id,"whole_data"=>$data);
        echo json_encode($response);
        exit();
    }

    public function editBook(Request $request) {
        $id = $request->post('id');
        $update = array();
        $update['book'] = $request->post('book');
        $update['author'] = $request->post('author');
        DB::table('book_details')->where('id',$id)->update($update);
        echo 'success';exit();
    }

    public function deleteBook(Request $request) {
        $id = $request->post('id');
        DB::table('book_details')->where('id',$id)->delete();
        echo 'success';exit();
    }
}
