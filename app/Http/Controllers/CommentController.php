<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function getComments(Request $request)
    {

        if ($request->query("order") == 'ASC') {
            $order = "ASC";
        } else {
            $order = "DESC";
        }

        $comments = Comment::orderBy('id', $order)->get();

        $data = [
            "meta" => [
                "total_comment" => count($comments)
            ],
            "data" => $comments
        ];

        // if ($request->query("book_id")) {

        //     // $comments = Comment::where('book_isbn', "$book_id")->orderBy('id', $order);
        // } else {
        // }

        return response()->json([
            'code' => '000',
            'message' => 'Available comment(s) ! ',
            'data' => $data
        ], 200);
    }

    public function getSingleComment(Request $request, $book_id)
    {

        if ($request->query("order") == 'ASC') {
            $order = "ASC";
        } else {
            $order = "DESC";
        }

        $comments = Comment::where('book_id', $book_id)->orderBy('id', $order)->get();

        $data = [
            "meta" => [
                "total_comment" => count($comments)
            ],
            "data" => $comments
        ];

        // if ($request->query("book_id")) {

        //     // $comments = Comment::where('book_isbn', "$book_id")->orderBy('id', $order);
        // } else {
        // }

        return response()->json([
            'code' => '000',
            'message' => 'Available comment(s) ! ',
            'data' => $data
        ], 200);
    }

    public function create(Request $request)
    {


        // Ghana Code
        $gpsName = $request->address;

        $validator = Validator::make($request->all(), [
            'comment' => 'required | max:500',
            'book_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => '422',
                'message' => 'Validation Error(s)',
                'data' => $validator->errors()
            ], 422);
        }

        try {
            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->book_id = $request->book_id;
            $comment->ip_address = request()->getClientIp();


            if ($comment->save()) {

                return response()->json([
                    'code' => '000',
                    'message' => 'Comment created successfully !',
                    'data' => NULL
                ], 200);
            } else {

                return response()->json([
                    'code' => '599',
                    'message' => 'Failed to create comment ! ',
                    'data' => NULL
                ], 200);
            }
            //code...
        } catch (\Exception  $e) {
            return response()->json([
                'code' => '422',
                'message' => $e->getMessage(),
                'data' => NULL
            ], 500);
        }
    }
}