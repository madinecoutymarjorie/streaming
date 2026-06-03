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
        Schema::create('morceaux', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->integer('duree'); // En secondes
            $table->decimal('prix', 5, 2)->default(0.00); // Ex: 1.29 (0.00 = gratuit)
            
            // Clé étrangère pointant vers 'id' de la table 'albums'
            $table->foreignId('album_id')->constrained('albums')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('morceaus');
    }
};
