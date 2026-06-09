<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PolyStream - Streaming Musique</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-900 text-white font-sans">

    <div class="flex h-screen">
        <aside class="w-64 bg-gray-950 p-6 flex flex-col justify-between">
            <div>
                <h1 class="text-2xl font-bold text-green-500 mb-8">PolyStream</h1>
                <nav class="space-y-4">
                    <button onclick="loadTracks('free')" class="block w-full text-left hover:text-green-500 font-medium">🎵 Musiques Gratuites</button>
                    <button onclick="loadTracks('premium')" class="block w-full text-left hover:text-green-500 font-medium">💳 Musiques Premium</button>
                    <button onclick="loadPlaylists()" class="block w-full text-left hover:text-green-500 font-medium">📂 Mes Playlists</button>
                    <button onclick="loadInvoices()" class="block w-full text-left hover:text-green-500 font-medium">🧾 Mes Factures</button>
                </nav>
            </div>
            <div id="auth-section" class="border-t border-gray-800 pt-4">
                <p class="text-sm text-gray-400 mb-2">Statut : <span id="auth-status" class="text-red-500">Déconnecté</span></p>
                <button onclick="toggleAuth()" id="btn-auth" class="w-full bg-green-500 text-black font-bold py-2 px-4 rounded hover:bg-green-400 transition">
                    Se connecter
                </button>
            </div>
        </aside>

        <main class="flex-1 p-8 overflow-y-auto">
            <h2 id="content-title" class="text-3xl font-bold mb-6">Musiques Gratuites</h2>
            
            <div id="content-display" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                </div>
        </main>
    </div>

    <script>
        // Configuration de base de l'API Laravel
        const API_BASE_URL = '/api';
        
        // Simulation d'un token utilisateur (à remplacer par ton vrai système d'auth ex: Sanctum/Passport)
        let isLoggedIn = false;
        let mockToken = "Bearer SIMULATED_TOKEN_12345";

        // Headers à envoyer à l'API
        function getHeaders() {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };
            if (isLoggedIn) {
                headers['Authorization'] = mockToken;
            }
            return headers;
        }

        // 1. Charger les musiques (Gratuites ou Premium)
        async function loadTracks(type) {
            const title = document.getElementById('content-title');
            const display = document.getElementById('content-display');
            
            if (type === 'premium' && !isLoggedIn) {
                alert("Accès refusé. Vous devez être connecté pour voir les musiques payantes !");
                return;
            }

            title.innerText = type === 'free' ? "Musiques Gratuites" : "Musiques Premium (Achetables)";
            display.innerHTML = "<p class='text-gray-400'>Chargement...</p>";

            try {
                // Appel AJAX à ton API Laravel
                const response = await fetch(${API_BASE_URL}/tracks?type=${type}, {
                    method: 'GET',
                    headers: getHeaders()
                });
                
                if (!response.ok) throw new Error('Erreur réseau');
                const tracks = await response.json();

                display.innerHTML = ''; // On vide le chargement
                
                if(tracks.length === 0) {
                    display.innerHTML = "<p class='text-gray-400'>Aucun morceau disponible.</p>";
                    return;
                }

                tracks.forEach(track => {
                    display.innerHTML += `
                        <div class="bg-gray-800 p-5 rounded-lg shadow-lg hover:bg-gray-750 transition">
                            <h3 class="text-xl font-semibold">${track.title}</h3>
                            <p class="text-gray-400 text-sm">Artiste : ${track.album.artist.name}</p>
                            <p class="text-gray-400 text-sm">Album : ${track.album.title}</p>
                            <div class="text-xs text-gray-500 my-2">Durée : ${track.duration}s | Styles : ${track.styles.join(', ')}</div>
                            <div class="flex justify-between items-center mt-4">
                                <span class="text-green-400 font-bold">${track.price == 0 ? 'Gratuit' : track.price + ' €'}</span>
                                ${track.price > 0 ? 
                                    <button onclick="buyTrack(${track.id})" class="bg-blue-600 text-white text-xs px-3 py-1 rounded hover:bg-blue-500">Acheter</button> 
                                    : <button class="bg-green-600 text-white text-xs px-3 py-1 rounded">Écouter</button>
                                }
                            </div>
                        </div>
                    `;
                });
            } catch (error) {
                display.innerHTML = <p class="text-red-500">Erreur lors de la récupération des données.</p>;
            }
        }

        // 2. Acheter un morceau
        async function buyTrack(trackId) {
            if (!isLoggedIn) return alert("Connectez-vous pour acheter ce morceau.");
            
            try {
                const response = await fetch(${API_BASE_URL}/purchases, {
                    method: 'POST',
                    headers: getHeaders(),
                    body: JSON.stringify({ track_id: trackId })
                });

                if (response.ok) {
                    alert("Morceau acheté avec succès !");
                    loadTracks('premium');
                } else {
                    alert("Erreur lors de l'achat.");
                }
            } catch (error) {
                console.error(error);
            }
        }

        // 3. Charger les Playlists
        async function loadPlaylists() {
            if (!isLoggedIn) return alert("Veuillez vous connecter pour voir vos playlists.");
            // Logique fetch similaire vers ${API_BASE_URL}/playlists
            document.getElementById('content-title').innerText = "Mes Playlists";
            document.getElementById('content-display').innerHTML = "<p class='text-gray-400'>Fonctionnalité Playlists (Appel à /api/playlists)</p>";
        }

        // 4. Charger la Facturation
        async function loadInvoices() {
            if (!isLoggedIn) return alert("Veuillez vous connecter pour voir vos factures.");
            // Logique fetch similaire vers ${API_BASE_URL}/invoices ou /purchases
            document.getElementById('content-title').innerText = "Mes Factures";
            document.getElementById('content-display').innerHTML = "<p class='text-gray-400'>Fonctionnalité Factures (Appel à /api/purchases)</p>";
        }

        // Gestionnaire de connexion fictif pour la démo
        function toggleAuth() {
            isLoggedIn = !isLoggedIn;
            document.getElementById('auth-status').innerText = isLoggedIn ? "Connecté" : "Déconnecté";
            document.getElementById('auth-status').className = isLoggedIn ? "text-green-500" : "text-red-500";
            document.getElementById('btn-auth').innerText = isLoggedIn ? "Se déconnecter" : "Se connecter";
            loadTracks('free');
        }

        // Charger les musiques gratuites au démarrage
        document.addEventListener('DOMContentLoaded', () => loadTracks('free'));
    </script>
</body>
</html>