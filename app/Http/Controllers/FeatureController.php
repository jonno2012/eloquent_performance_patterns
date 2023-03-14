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

    public function indexOrderingByCustomAlgorithms()
    {
        $features = Feature::query()
            ->withCount('comments', 'votes') // withCount will add the number of related records
                ->when(request('sort'), function($query, $sort) {
                    switch ($sort) {
                        case 'title': return $query->orderBy('title', request('direction'));
                        case 'status': return $query->orderByStatus(request('direction'));
                        case 'activity': return $query->orderByActivity(request('direction'));
                    }
            })
            ->latest()
            ->paginate();

        return view('features.index', ['features' => $features]);
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
//        $feature->load('comments.user', 'comments.feature.comments'); // contains circular relationship
        $feature->load('comments.user');
        $feature->comments->each->setRelationship('feature', $feature); // set the relationship manually to avoid circular relationship

        return view('feature', ['feature' => $feature]);


        return view('feature', ['feature' => $feature]);
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
