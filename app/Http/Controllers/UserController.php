<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Login;

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
            // we don't want to use a subquery when ordering by a hasOne relationships
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
            ->orderBy('companies.name')
            ->with('company')
            ->paginate();

        return view('users', ['users' => $users]);
    }

    public function indexBelongsTo()
    {
        // when ordering with a belongsTo relationship use the join approach

        // The join approach 1ms
        $users = User::query(0)
            ->select('users.*')
            ->join('companies', 'companies.id', '=', 'users.company_id')
            ->orderBy('companies.name')
            ->with('company')
            ->paginate();

        // the subquery approach 77ms
//        $user = User::query()
//            ->orderBy(Company::select('name')
//            ->whereColumn('id', 'users.company_id')
//            ->orderBy('name')
//                ->take(1)
//            );

        return view('users', ['users' => $users]);
    }

    public function indexHasManyOrderBy()
    {
        // we will try and order by a HasMAny relationship. This is tricky because you
        // can get a record returned for every instance of the hasMany rel of the belongsTo
        // i.e. this will return a row for the same user for every time they have been logged in.

        // we can solve this by groupBy users.id and then by telling sql which hasMany rel to order by (otherwise you
        // will get an sql error (..clause is not in group by clause... because sql will not know which instance of
        // the hasMany to use to do the orderBy

//        $users = User::query() // 42ms
//            ->select('users.*') // only get these columns. you can omit to return all.
//            ->join('logins', 'logins.users_id', '=', 'users.id')
//            ->groupBy('users.id')
//            ->orderByRaw('max(logins.created_at)')
//            ->withLastLogin() // dynamic rel
//            ->paginate();

        // when order by a hasMany use a subquery like this
        $users = User::query()
            ->orderByLastLogin()
            ->withLastLogin() // dynamic rel
            ->paginate();

        return View::make('users', ['users' => $users]);
    }

    public function indexOrderByNull()
    {
        $users = User::query()
            ->whereBirthdayThisWeek()
            ->when(request('sort') === 'town', function($query) {
                $query->orderByRaw('town is NULL')
                    ->orderBy('town', request('direction'));
            })
            ->paginate();

        return view('users.index', ['users' => $users]);
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
