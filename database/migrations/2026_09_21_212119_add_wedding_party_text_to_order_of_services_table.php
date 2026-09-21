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
        Schema::table('order_of_services', function (Blueprint $table) {
            $table->string('wedding_party_title')->nullable();
            $table->text('wedding_party_intro')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_of_services', function (Blueprint $table) {
            $table->dropColumn(['wedding_party_title', 'wedding_party_intro']);
        });
    }
};
