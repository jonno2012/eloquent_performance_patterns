<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
//        $users = User::query()
//            ->with('company')
//            ->orderBy('name')
//            ->paginate();

        // sub queries can be used to get a small result set from a related model:
        $users = User::query()
            ->withLastLogin()
            ->orderBy('name')
            ->paginate();

        return view('users.index', ['users' => $users]);
    }

    public function indexHasOne()
    {
            // we don't want to use a subquery when ordering by with a hasOne relationships
//        $users = User::query()
//            ->orderBy(Company::select('name')
//                ->whereColumn('users_id', 'users.id')
//                ->orderBy('name')
//                ->take(1)
//            )
//            ->with('company')
//            ->paginate();

        $users = User::query(0)
            ->join('companies', 'companies', '=', 'users.id')
            ->with('company')
            ->orderBy('companies.name')
            ->paginate();

        return view('users', ['users' => $users]);
    }

    public function searchIndex()
    {
        $r = Feature::query();
        $users = User::query()
            ->search(request('search'))
            ->with('company')
            ->paginate();

        return view('users.index', ['users' => $users]);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
