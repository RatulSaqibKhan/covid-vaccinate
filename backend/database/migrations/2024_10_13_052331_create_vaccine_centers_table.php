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
        Schema::create('vaccine_centers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique()->index();
            $table->string('address', 255);
            $table->integer('daily_capacity');
            $table->date('available_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vaccine_centers');
    }
};
