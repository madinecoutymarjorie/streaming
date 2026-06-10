<?php

namespace App\Http\Controllers;
use App\Models\Playlist;

use Illuminate\Http\Request;

class PlaylistController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // On récupère l'ID de l'utilisateur connecté
        $userId = auth('sanctum')->id();
        
        // CORRECTION : On utilise 'utilisateur_id' (le vrai nom de ta colonne)
        $playlists = Playlist::where('utilisateur_id', $userId)->get();

        return response()->json($playlists);
    }

    public function show($id)
    {
        // 1. On cherche la playlist avec son ID
        $playlist = Playlist::find($id);

        // 2. Si elle n'existe pas en BDD, on renvoie une erreur 404
        if (!$playlist) {
            return response()->json(['message' => 'Playlist introuvable.'], 404);
        }

        // 3. Sécurité : Vérifier que la playlist appartient à l'utilisateur connecté
        // CORRECTION : Ici aussi, on remplace par 'utilisateur_id'
        if ($playlist->utilisateur_id != auth('sanctum')->id()) {
            return response()->json(['message' => 'Accès interdit à cette playlist.'], 403);
        }

        // 4. On renvoie la playlist trouvée
        return response()->json($playlist);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation des données reçues (le titre est obligatoire)
        $request->validate([
            'titre' => 'required|string|max:255',
        ]);

        // 2. Création de la playlist liée à l'utilisateur connecté
        $playlist = new Playlist();
        $playlist->titre = $request->input('titre');
        $playlist->utilisateur_id = auth('sanctum')->id(); // Récupère l'ID de l'user connecté
        $playlist->save();

        // 3. On retourne la playlist créée avec un code HTTP 201 (Created)
        return response()->json([
            'message' => 'Playlist créée avec succès !',
            'playlist' => $playlist
        ], 201);
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
