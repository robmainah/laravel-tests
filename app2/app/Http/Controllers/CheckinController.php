<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckinController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Book $book)
    {
        try {
            $book->checkin(Auth::user());
        } catch (\Throwable $th) {
            return response([], 404);
        }
    }
}
