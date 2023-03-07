<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
    $statuses = (object) [];
    $statuses->requested = Feature::where('status', 'Requested')->count();
    $statuses->planned = Feature::where('status', 'Planned')->count();
    $statuses->completed = Feature::where('status', 'Completed')->count();
    // this will run a query for every value which is bad. Below is how we should do it:
//    select
//        count(case when status = 'Requested' then 1 end) as requested,
//        count(case when status = 'Planned' then 1 end) as planned,
//        count(case when status = 'Completed' then 1 end) as completed,
//    from features

        // this is achieved in Eloquent by doing the following:

        $statuses = Feature::toBase()
            ->selectRaw("count(case when status = 'Requested' then 1 end) as requested")
            ->selectRaw("count(case when status = 'Planned' then 1 end) as planned")
            ->selectRaw("count(case when status = 'Completed' then 1 end) as completed")
            ->first();




        $features = Feature::query()
            ->withCount('comments')
            ->paginate();

        return view('users.edit', []);



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
    public function show(Feature $feature)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Feature $feature)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Feature $feature)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Feature $feature)
    {
        //
    }
}
