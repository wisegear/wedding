<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_uploads', function (Blueprint $table) {
            $table->string('original_path')->nullable()->after('original_filename');
            $table->string('display_path')->nullable()->after('original_path');
            $table->foreignId('uploaded_by')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        DB::table('gallery_uploads')->update([
            'uploaded_by' => DB::raw('user_id'),
            'original_path' => DB::raw('path'),
            'display_path' => DB::raw('path'),
        ]);

        Schema::table('gallery_uploads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['path', 'caption']);
        });
    }

    public function down(): void
    {
        Schema::table('gallery_uploads', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->string('path')->nullable()->after('original_filename');
            $table->string('caption')->nullable()->after('display_path');
        });

        DB::table('gallery_uploads')->update([
            'user_id' => DB::raw('uploaded_by'),
            'path' => DB::raw('display_path'),
            'caption' => null,
        ]);

        Schema::table('gallery_uploads', function (Blueprint $table) {
            $table->dropConstrainedForeignId('uploaded_by');
            $table->dropColumn(['original_path', 'display_path']);
        });
    }
};
