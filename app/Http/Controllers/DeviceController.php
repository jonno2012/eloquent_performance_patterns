<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // by default, DBs and programming languages will look at strings which contain numbers one at a time
        // and will not treat the numbers as a whole. For example, the number 11 will be sorted higher than the
        // number 3, because the language looks at the digits one at a time.
        // PHP and other languages offer 'natural sorting' where numbers are treated as a whole figure.

//        $devices = ['iPhone 3', 'iPhone 11'];

//        sort($devices, SORT_NATURAL); // iPhone 3 will come first

        $devices = Device::query()
            ->orderByRaw('naturalsort(name)')
            ->paginate();

        return view('devices.index', ['devices' => $devices]);
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
    public function show(Device $device)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Device $device)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Device $device)
    {
        //
    }
}
