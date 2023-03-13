<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // this can be an expensive query. consider using caching when ordering by belongsToMany relationships
        // You could use 'foreign key caching' by simply adding a 'last_checkout_id' column on the books table. see migration
        $books = Book::query()
            ->orderBy(User::select('name')
            ->join('checkouts', 'checkouts.user_id', '=', 'users.id')
            ->whereColumn('checkouts.book_id', 'books.id')
            ->latest('checkouts.borrowed_at')
            ->take(1)
            );

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        //
    }
}
