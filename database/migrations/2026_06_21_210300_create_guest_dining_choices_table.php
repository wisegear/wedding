<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guest_dining_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wedding_guest_id')->constrained()->cascadeOnDelete();
            $table->foreignId('dining_option_id')->nullable()->constrained()->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guest_dining_choices');
    }
};
