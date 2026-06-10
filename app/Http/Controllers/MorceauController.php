<?php

namespace App\Http\Controllers;

use App\Models\Morceau;
use Illuminate\Http\Request;

class MorceauController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->type;

        if ($type == 'free') {
            return Morceau::where('prix',0)->get();
        }

        return Morceau::where('prix','>',0)->get();
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
    public function show(Morceau $morceau)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Morceau $morceau)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Morceau $morceau)
    {
        //
    }
}
