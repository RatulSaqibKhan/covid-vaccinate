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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('nid', 20)->unique()->index();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('phone', 13)->unique();
            $table->bigInteger('vaccine_center_id')->index();
            $table->timestamp('registered_at')->nullable();
            $table->enum('status', ['Not Scheduled', 'Scheduled', 'Vaccinated'])->default('Not Scheduled');
            $table->date('scheduled_date')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
