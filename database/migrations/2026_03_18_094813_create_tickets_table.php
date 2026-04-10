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
    Schema::create('tickets', function (Blueprint $table) {
        $table->id();
        $table->string('client');
        $table->string('sujet');
        $table->enum('priorite', ['basse', 'moyenne', 'haute'])->default('moyenne');
        $table->enum('statut', ['ouvert', 'en cours', 'fermé'])->default('ouvert');
        $table->date('due')->nullable();
        $table->timestamps();
    });
    }
   
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
