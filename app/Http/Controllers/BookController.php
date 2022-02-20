<?php

namespace App\Http\Controllers;

use App\Models\Anapioficeandfire;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class BookController extends Controller
{

    public function transformBookData($book)
    {
        // get book id from book url
        if (preg_match("/\/(\d+)$/", $book->url, $recordMatch)) {
            $book_id = $recordMatch[1];
        }

        // SET NEW OBJECT ARRAY FROM TRANSFORMATION
        $i_books["url"] = $book->url;
        $i_books["book_id"] = $book_id;
        $i_books["name"] = $book->name;

        $i_books["number_of_authors"] = count($book->authors);
        $i_books["authors"] = $book->authors;

        $i_books["number_of_characters"] = count($book->characters);
        $i_books["characters"] = $book->characters;

        // QUERY COMMENTS ALONG SIDE BY BOOK ID
        $comment = Comment::where('book_id', $book_id)->orderByDesc("id")->get();

        $i_books["number_of_comments"] = count($comment);
        $i_books["comment"] = $comment;

        return $i_books;
    }

    public function getBooks(Request $request)
    {
        try {

            $ice_and_fire = new Anapioficeandfire();

            // CHECKING QUERY PARAMS ( name, fromReleaseDate )

            if ($request->query("name")) {
                $book_name = $request->query("name");
                $books = $ice_and_fire->getBookByName($book_name);
            } else if ($request->query("fromReleaseDate")) {
                $fromReleaseDate = $request->query("fromReleaseDate");
                $books = $ice_and_fire->getBookByFromReleaseDate($fromReleaseDate);
            } else {
                $books = $ice_and_fire->getAllBooks();
            }

            // CHECK FOR ORDER ( Descending | Ascending order )

            if ($request->query("order") == 'DESC') {
                //  Reverse array to get Descending order
                $order = "DESC";
                $books = array_reverse($books);
            } else {
                //  Books already coming in Ascending order
                $order = "ASC";
            }


            //  Transforming book data.

            $i_books = [];
            foreach ($books as $book) {
                array_push($i_books, (array) $this->transformBookData($book));
            }


            return response()->json([
                'code' => '200',
                'message' => "Books available",
                'data' => $i_books
            ], 200);
        } catch (\Exception  $e) {
            return response()->json([
                'code' => '500',
                'message' => $e->getMessage(),
                'data' => NULL
            ], 500);
        }
    }

    public function getSingleBook($book_id)
    {
        try {
            //code...


            $ice_and_fire = new Anapioficeandfire();
            $response = $ice_and_fire->getBookByID($book_id);

            $book_i = $response;


            //  Transforming book data.
            $book = $this->transformBookData($book_i);

            return response()->json([
                'code' => '200',
                'message' => "Book available",
                'data' => [$book]
            ], 200);
        } catch (\Exception  $e) {
            return response()->json([
                'code' => '500',
                'message' => $e->getMessage(),
                'data' => NULL
            ], 500);
        }
    }
}