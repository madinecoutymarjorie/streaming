<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achat;
use App\Models\Morceau;

class AchatController extends Controller
{
    public function index()
    {
        // Récupère l'historique d'achats de l'utilisateur connecté
        $achats = Achat::where('utilisateur_id', auth('sanctum')->id())->get();
        return response()->json($achats);
    }

    public function store(Request $request)
    {
        // 1. Validation : On vérifie que le front-end a bien envoyé un "track_id"
        $request->validate([
            'track_id' => 'required|integer'
        ]);

        // 2. On récupère le morceau en BDD pour connaître son prix réel
        $morceau = Morceau::find($request->input('track_id'));

        if (!$morceau) {
            return response()->json(['message' => 'Morceau introuvable.'], 404);
        }

        // 3. Sécurité : Vérifier si l'utilisateur n'a pas déjà acheté ce morceau
        $dejaAchete = Achat::where('utilisateur_id', auth('sanctum')->id())
                           ->where('morceau_id', $morceau->id)
                           ->exists();

        if ($dejaAchete) {
            return response()->json(['message' => 'Vous possédez déjà ce morceau !'], 400);
        }

        // 4. Création de l'achat lié à l'utilisateur connecté
        $achat = new Achat();
        $achat->utilisateur_id = auth('sanctum')->id(); // Récupère l'ID via le Token Sanctum
        $achat->morceau_id     = $morceau->id;
        $achat->prix_paye      = $morceau->prix; // On prend le prix du morceau trouvé en BDD
        $achat->date_achat     = now();          // Date actuelle
        $achat->save();

        return response()->json([
            'message' => 'Morceau acheté avec succès !',
            'achat'   => $achat
        ], 201);
    }
}
