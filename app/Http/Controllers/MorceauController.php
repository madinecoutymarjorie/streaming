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
        $type = $request->query('type');

        // Cas 1 : Demande de morceaux Premium
        if ($type === 'premium') {
            // On vérifie si le token Sanctum est présent et valide
            if (!auth('sanctum')->user()) {
                return response()->json(['message' => 'Accès refusé.'], 401);
            }
            return response()->json(Morceau::where('prix', '>', 0)->get());
        }

        // Cas 2 : Demande de morceaux Gratuits (ou par défaut)
        // Remplace 'free' par 'gratuit' selon ce que tu as écrit en BDD
        return response()->json(Morceau::where('prix', 0)->get());
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
