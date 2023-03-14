<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years = Post::query()
            ->select('id', 'title', 'slug', 'published_at', 'author_id')
            ->with('author:id,name')
            ->latest('published_at')
            ->get()
            ->groupBy(fn ($post) => $post->published_at->year);

        return view('posts', ['years' => $years]);
    }

    public function indexFullTextSearching()
    {
        $posts = Post::query()
            ->with('author')
            ->when(request('search'), function ($query, $search) {
                $query->selectRaw('*, match(title, body) against(? in boolean mode) as score', [$search])
                    ->whereRaw('match(title, body) against(? in boolean mode)', [$search]);
            }, function ($query) {
                $query->latest('published_at'); // the third arg in the when callback is triggered when the truthy check fails
                // and so will be called when there is no search query
            })
            ->paginate();

        return view('posts.index', ['posts' => $posts]);

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
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
