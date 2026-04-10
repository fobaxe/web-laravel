<?php
// Ce fichier n'est nécessaire que si votre migration
// 2026_04_08_000003 n'a pas déjà onDelete('cascade') sur projet_id.
// Vérifiez votre migration existante avant d'exécuter celle-ci.
// La contrainte onDelete('set null') existante sur projet_id dans tickets
// signifie que supprimer un projet met projet_id à NULL dans les tickets.
// Si vous préférez supprimer les tickets en cascade, changez-la en 'cascade'.

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            // Supprimer l'ancienne contrainte set null
            $table->dropForeign(['projet_id']);
            // Recréer avec cascade (les tickets sont supprimés avec leur projet)
            $table->foreign('projet_id')
                  ->references('id')->on('projets')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['projet_id']);
            $table->foreign('projet_id')
                  ->references('id')->on('projets')
                  ->onDelete('set null');
        });
    }
};
