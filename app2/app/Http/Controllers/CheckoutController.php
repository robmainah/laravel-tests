<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
	public function __construct()
	{
        $this->middleware('auth');
	}

    public function store(Book $book)
    {
        $book->checkout(Auth::user());
    }
}
