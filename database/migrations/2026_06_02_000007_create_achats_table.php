<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('achats', function (Blueprint $table) {
            $table->id(); // Optionnel mais recommandé pour un historique
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('morceau_id')->constrained('morceaux')->onDelete('cascade');
            
            // Attributs spécifiques portés par l'association
            $table->decimal('prix_paye', 5, 2); // Sauvegarde le prix à l'achat
            $table->timestamp('date_achat')->useCurrent(); // Date de la transaction
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};
