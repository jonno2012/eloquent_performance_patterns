<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        Auth::login(User::where('name', 'Sarah Seller')->first());

        $customers = Customer::query()
            ->with('salesRep')
            ->visibleTo(Auth::user()) // better to implement on the db layer rather than the template layer using a policy
            ->orderBy('name')
            ->paginate();

        return view ('customers', ['customers' => $customers]);
    }

    public function indexNPlus1()
    {
        $customers = Customer::query()
            ->with('salesRep')
            ->orderBy('name')
            ->paginate();

        return view ('customers', ['customers' => $customers]);
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
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Customer $customer)
    {
        //
    }
}
