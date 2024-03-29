<?php

namespace App\Http\Controllers;

use App\Models\Book;

class BookController extends Controller
{
    public function store()
    {
        $book = Book::create($this->validateRequest());
        return redirect($book->path());
    }

    public function update(Book $book)
    {
        $book->update($this->validateRequest());

        return redirect($book->path());
    }

    public function delete(Book $book)
    {
        $book->delete();

        return redirect('/books');
    }

    private function validateRequest()
    {
        return request()->validate([
            'title' => 'required',
            'author_id' => 'required',
        ]);
    }
}
