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
        Schema::create('violations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('license_plate');
            $table->string('violation_type');
            $table->string('location');
            $table->dateTime('date_time');
            $table->decimal('fine_amount', 8, 2);
            $table->enum('status', ['pending', 'paid', 'disputed'])->default('pending');
            $table->string('evidence')->nullable();
            $table->text('dispute_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('violations');
    }
};