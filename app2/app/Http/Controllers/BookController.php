<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BookController extends Controller
{ 
    public function store()
    {
        return Book::create($this->validateRequest());
    }

    public function update(Book $book)
    {
        $book->update($this->validateRequest());

        return $book;
    }

    private function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'author' => 'required',
        ]);
    }
}
