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
                <button onclick="createNewPlaylist()" class="block w-full text-left text-sm text-green-400 hover:text-green-300 font-medium mt-4">+ Nouvelle Playlist</button>
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
        //let mockToken = "Bearer SIMULATED_TOKEN_12345";
        let authToken = "";

        // Headers à envoyer à l'API
        function getHeaders() {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        };
        if (isLoggedIn && authToken) {
            headers['Authorization'] = `Bearer ${authToken}`;
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
                const response = await fetch(`${API_BASE_URL}/morceaux?type=${type}`, {
                    method: 'GET',
                    headers: getHeaders()
                });
                
                if (!response.ok) throw new Error('Erreur réseau');
                const tracks = await response.json();

                display.innerHTML = ''; 
                
                if(tracks.length === 0) {
                    display.innerHTML = "<p class='text-gray-400'>Aucun morceau disponible.</p>";
                    return;
                }

                tracks.forEach(track => {
                    // Ajout d'un bouton d'achat si le morceau est payant (> 0€)
                    const buttonHtml = track.prix > 0 
                        ? `<button onclick="buyTrack(${track.id})" class="mt-2 w-full bg-green-500 text-black text-xs font-bold py-1 rounded hover:bg-green-400">Acheter</button>` 
                        : '';

                    display.innerHTML += `
                        <div class="bg-gray-800 p-4 rounded flex flex-col justify-between h-full">
                            <div>
                                <h3 class="font-bold text-lg">${track.titre}</h3>
                                <p class="text-gray-400">${track.prix} €</p>
                            </div>
                            ${buttonHtml}
                        </div>
                    `;
                });
            } catch (error) {
                display.innerHTML = `<p class="text-red-500">Erreur lors de la récupération des données.</p>`;
            }
        }

        // 2. Acheter un morceau
        async function buyTrack(trackId) {
            if (!isLoggedIn) return alert("Connectez-vous pour acheter ce morceau.");
            
            try {
                const response = await fetch(`${API_BASE_URL}/achats`, {
                    method: 'POST',
                    headers: getHeaders(), // Injecte le Content-Type et surtout le Bearer Token
                    body: JSON.stringify({ track_id: trackId }) // Envoie la clé attendue par le validateur Laravel
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.message); // Affiche "Morceau acheté avec succès !"
                    loadTracks('premium'); // Rafraîchit l'affichage
                } else {
                    // Affiche le message d'erreur personnalisé de Laravel (ex: "Vous possédez déjà ce morceau !")
                    alert(data.message || "Erreur lors de l'achat.");
                }
            } catch (error) {
                console.error(error);
                alert("Impossible de contacter le serveur d'achats.");
            }
        }

        // 3. Charger les Playlists
        async function loadPlaylists() {
            if (!isLoggedIn) return alert("Veuillez vous connecter pour voir vos playlists.");
            
            document.getElementById('content-title').innerText = 'Mes Playlists';
            const display = document.getElementById('content-display');
            display.innerHTML = "<p class='text-gray-400'>Chargement...</p>";

            try {
                const response = await fetch(`${API_BASE_URL}/playlists`, {
                    method: 'GET',
                    headers: getHeaders() // <-- Envoie le token !
                });

                if (!response.ok) throw new Error();
                const playlists = await response.json();
                
                let html = '';
                if(playlists.length === 0) {
                    html = "<p class='text-gray-400'>Aucune playlist trouvée.</p>";
                } else {
                    playlists.forEach(playlist => {
                        html += `
                            <div class="bg-gray-800 p-4 rounded">
                                <h3 class="font-bold">${playlist.titre}</h3>
                            </div>
                        `;
                    });
                }
                display.innerHTML = html;
            } catch (error) {
                display.innerHTML = "<p class='text-red-500'>Erreur de chargement des playlists.</p>";
            }
        }

        // 5. Créer une nouvelle playlist
        async function createNewPlaylist() {
            if (!isLoggedIn) return alert("Veuillez vous connecter pour créer une playlist.");

            // On demande le nom de la playlist via un prompt
            const title = prompt("Entrez le titre de votre nouvelle playlist :");
            if (!title || title.trim() === "") return;

            try {
                const response = await fetch(`${API_BASE_URL}/playlists`, {
                    method: 'POST',
                    headers: getHeaders(), // Contient Content-Type: application/json ET le Token Bearer
                    body: JSON.stringify({ titre: title })
                });

                const data = await response.json();

                if (response.ok) {
                    alert(data.message);
                    // On recharge la vue des playlists pour voir la nouvelle apparaître !
                    loadPlaylists(); 
                } else {
                    alert("Erreur lors de la création : " + (data.message || "Erreur inconnue"));
                }
            } catch (error) {
                alert("Impossible de joindre le serveur.");
            }
        }

        // 4. Charger la Facturation
        async function loadInvoices() {
            if (!isLoggedIn) return alert("Veuillez vous connecter pour voir vos factures.");
            
            document.getElementById('content-title').innerText = 'Mes Achats';
            const display = document.getElementById('content-display');
            display.innerHTML = "<p class='text-gray-400'>Chargement...</p>";

            try {
                const response = await fetch(`${API_BASE_URL}/achats`, {
                    method: 'GET',
                    headers: getHeaders()
                });

                if (!response.ok) throw new Error();
                const achats = await response.json();

                let html = '';
                if(achats.length === 0) {
                    html = "<p class='text-gray-400'>Aucun achat effectué.</p>";
                } else {
                    achats.forEach(achat => {
                        html += `
                            <div class="bg-gray-800 p-4 rounded">
                                <p class="font-medium text-green-500">Prix payé : ${achat.prix_paye} €</p>
                                <p class="text-sm text-gray-400">Date : ${achat.date_achat}</p>
                            </div>
                        `;
                    });
                }
                display.innerHTML = html;
            } catch (error) {
                display.innerHTML = "<p class='text-red-500'>Erreur de chargement des factures.</p>";
            }
        }


        // Gestionnaire de connexion fictif pour la démo
        async function toggleAuth() {
            if (isLoggedIn) {
                // Mode déconnexion
                isLoggedIn = false;
                authToken = "";
                updateAuthUI();
                loadTracks('free');
            } else {
                // Mode connexion : On demande les identifiants
                const email = prompt("Entrez votre email :", "user@example.com");
                const password = prompt("Entrez votre mot de passe :");

                if(!email || !password) return;

                try {
                    const response = await fetch(`${API_BASE_URL}/login`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                        body: JSON.stringify({ email: email, password: password })
                    });

                    if(response.ok) {
                        // Ta route /login renvoie directement la chaîne du token textuel
                        authToken = await response.text();
                        console.log("Mon token précieux :", authToken); // afficher le token
                        isLoggedIn = true;
                        updateAuthUI();
                        loadTracks('premium'); // Charge directement les musiques premium une fois connecté !
                    } else {
                        alert("Identifiants incorrects ou erreur serveur.");
                    }
                } catch (error) {
                    alert("Impossible de joindre le serveur d'authentification.");
                }
            }
        }

        function updateAuthUI() {
            document.getElementById('auth-status').innerText = isLoggedIn ? "Connecté" : "Déconnecté";
            document.getElementById('auth-status').className = isLoggedIn ? "text-green-500" : "text-red-500";
            document.getElementById('btn-auth').innerText = isLoggedIn ? "Se déconnecter" : "Se connecter";
        }

        // Charger les musiques gratuites au démarrage
        document.addEventListener('DOMContentLoaded', () => loadTracks('free'));
    </script>
</body>
</html>