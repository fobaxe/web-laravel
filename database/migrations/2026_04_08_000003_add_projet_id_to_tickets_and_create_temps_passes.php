<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Relier les tickets à un projet
        Schema::table('tickets', function (Blueprint $table) {
            $table->foreignId('projet_id')->nullable()->constrained('projets')->onDelete('set null');
        });

        // Table pour le suivi du temps passé
        Schema::create('temps_passes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('tickets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('date');
            $table->unsignedSmallInteger('duree'); // durée en minutes
            $table->string('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('temps_passes');
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['projet_id']);
            $table->dropColumn('projet_id');
        });
    }
};
